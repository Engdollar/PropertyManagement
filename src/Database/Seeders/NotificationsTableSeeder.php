<?php

namespace Workdo\PropertyManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;


class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // email notification
        $notifications = [
            'New Property Invoice','Property Invoice Payment Create'
        ];
        $permissions = [
            'property invoice manage',
            'property payment manage'


        ];
        foreach($notifications as $key=>$n){
            $ntfy = Notification::where('action',$n)->where('type','mail')->where('module','PropertyManagement')->count();
            if($ntfy == 0){
                $new = new Notification();
                $new->action = $n;
                $new->status = 'on';
                $new->permissions = $permissions[$key];
                $new->module = 'PropertyManagement';
                $new->type = 'mail';
                $new->save();
            }
        }
    }
}
