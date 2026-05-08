<?php

namespace App\Console\Commands;

use App\Models\Dose;
use App\Models\Registration;
use App\Models\Vaccine;
use Illuminate\Console\Command;

class ScheduleAppointment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appoint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set appointments for the next doses on the next available days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registration = Registration::all();

        if($registration->isEmpty())
        {
            printf("Nobody registered yet\n");
        }
        else{
            printf("Traversing through the registrations...\n");
            $vaccine = Vaccine::first();
            $doseTypes = ['first', 'second', 'booster'];

            foreach($registration as $registration)
            {
                foreach($doseTypes as $doseType)
                {
                    $dose = $registration->doses()
                        ->where('dose_type', $doseType)
                        ->first();
                    
                    if($dose)
                    {
                        if($dose->taken_date == null)
                        {
                            // already appointed
                            break;
                        }
                    }
                    else{
                        $this->appointToNextDate($registration, $doseType, $vaccine);
                        break;
                    }
                }
            }
            printf("Finished!\n");
        }
    }

    public function appointToNextDate($registration, $doseType, $vaccine)
    {
        $date = today();

        $dose = Dose::create([
            'registration_id' => $registration->id,
            'vaccine_id' => $vaccine->id,
            'dose_type' => $doseType,
            'scheduled_date' => $date,
            'given_by' => null,
            'taken_date' => null,
        ]);

        printf("Assigned:\n");
        printf("candidate: %s, does: %s, date: %s\n", $registration->user->name, $doseType, $date);
        printf("\n");
    }
}
