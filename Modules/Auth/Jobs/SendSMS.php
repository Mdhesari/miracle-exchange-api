<?php

namespace Modules\Auth\Jobs;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Services\SMSIR;

class SendSMS
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $mobile;

    protected string $templateId;

    protected array $parameters;

    /**
     * SendSMS constructor.
     * @param string $mobile
     * @param int $templateId
     * @param array $parameters
     */

    public function __construct(string $mobile, int $templateId, array $parameters)
    {
        $this->mobile = $mobile;

        $this->templateId = $templateId;

        $this->parameters = $parameters;

        $this->onQueue('send-sms-queue');
    }

    public function handle()
    {
        $this->SMS()->VerifyLookup($this->mobile, $this->parameters[0] ?? null, null, null, $this->templateId, null);
    }

    private function SMS()
    {
        return app('kavenegar');
    }
}
