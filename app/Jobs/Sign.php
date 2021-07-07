<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\UserSign;
use Throwable;

class Sign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;
    public $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id,$type)
    {
        $this->user_id = $user_id;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_sign = new UserSign();
        $user_sign->user_id = $this->user_id;
        $user_sign->type = $this->type;
        $user_sign->save();
        var_dump('success');
    }

    public function failed(Throwable $exception)
    {
        var_dump('failed');
    }
}
