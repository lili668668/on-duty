<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\EmployeeRepo;
use App\Repositories\GroupRepo;
use App\Services\EmployeeService;
use App\Services\GroupService;
use App\Services\LineBotService;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use InvalidArgumentException;
use Exception;

class PushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '推送 line 值日生的通知';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     * @return void 
     * @throws InvalidFormatException 
     * @throws InvalidArgumentException 
     * @throws Exception 
     */
    public function handle()
    {
        $today = Carbon::now()->startOfDay();
        $empployeeService = new EmployeeService(new EmployeeRepo());
        $lineBotService = new LineBotService();
        $groupService = new GroupService(new GroupRepo());
        $employees = $empployeeService->queryOnDuty($today);
        $tags = $employees->map(function ($employee)
        {
            return $employee->line_id;
        })->values()->all();
        $message = $lineBotService->createNotificationMessage($tags);
        $groups = $groupService->list();
        foreach ($groups as $group)
        {
            $lineBotService->pushMessage($group->group_id, [
                [
                    'type' => 'text',
                    'text' => $message
                ]
            ]);
        }
    }
}
