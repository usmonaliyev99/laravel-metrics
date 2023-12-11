<?php

namespace Usmonaliyev\LaravelMetrics\Console\Commands;

use CURLFile;
use Illuminate\Console\Command;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Support\Facades\Redis;

class NotifyReportCommand extends Command
{
    private PredisConnection $redis;

    public function __construct()
    {
        parent::__construct();

        $this->redis = Redis::connection(config('metric.connection'));
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metric:notify-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send metric report to telegram chats';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $counts = 'COUNTS.csv';
        $speeds = 'SPEEDS.csv';

        $this->createCountsReport($counts);

        $this->createSpeedsReport($speeds);

        $this->sendToTelegram($counts);

        $this->sendToTelegram($speeds);

        unlink($counts);
        unlink($speeds);
    }

    private function createCountsReport(string $name): void
    {
        $counts = $this->redis->zrevrange(config('metric.prefix.query.count'), 0, -1, 'WITHSCORES');
        $file = fopen($name, 'w');

        foreach ($counts as $sql => $count) {
            fputcsv($file, [$sql, $count]);
        }

        fclose($file);

        $this->redis->del(config('metric.prefix.query.count'));
    }

    private function createSpeedsReport(string $name): void
    {
        $speeds = $this->redis->zrevrange(config('metric.prefix.query.speed'), 0, -1, 'WITHSCORES');
        $file = fopen($name, 'w');

        foreach ($speeds as $sql => $speed) {
            fputcsv($file, [$sql, $speed]);
        }

        fclose($file);

        $this->redis->del(config('metric.prefix.query.speed'));
    }

    private function sendToTelegram(string $file): void
    {
        $botToken = config('metric.notify.bot_token');

        $file = base_path() . '/' . $file;
        $apiEndpoint = "https://api.telegram.org/bot$botToken/sendDocument";

        if (!file_exists($file)) {
            return;
        }

        $postFields = [
            'chat_id' => config('metric.notify.chat_id'),
            'caption' => config('metric.notify.caption'),
            'document' => new CURLFile($file),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->error('Error sending file: ' . curl_error($ch));
        } else {
            $responseData = json_decode($response, true);
            if ($responseData['ok']) {
                $this->info('Excel file sent successfully!');
            } else {
                $this->info('Error sending file: ' . $responseData['description']);
            }
        }

        curl_close($ch);
    }
}
