<?php

namespace Modules\Auth\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $mobile;

    protected int|string $templateId;

    protected array $parameters;

    /**
     * SendSMS constructor.
     * @param string $mobile
     * @param int|string $templateId
     * @param array $parameters
     */

    public function __construct(string $mobile, int|string $templateId, array $parameters)
    {
        $this->mobile = $mobile;

        $this->templateId = $templateId;

        $this->parameters = $parameters;

        $this->onQueue('high');
    }

    public function handle()
    {
        $this->SMS()->VerifyLookup($this->mobile, $this->parameters[0] ?? null, $this->parameters[1] ?? null, $this->parameters[2] ?? null, $this->templateId, $this->parameters[3] ?? null);
    }

    private function SMS()
    {
        return app('kavenegar');
    }
}
