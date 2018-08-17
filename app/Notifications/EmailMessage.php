<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Notifications;

use App\Models\Tickets;
use App\User;

/**
 * Description of EmailMessage
 *
 * @author FMCJr
 */
class EmailMessage {
    
    protected $from = "noreply@aaasne.com";
    protected $to;
    protected $links;
    protected $subject;
    protected $body;
    
    public $type =1;
    
    public function __construct() {
       $this->to = array();
       $this->links = array();
       $this->body = array();
       $this->subject ='';
    }
    /**
     * 
     * @param int $ticketid
     * @param string $msgcontent
     */
    public function sendEmailToSubscribers($ticketid,$msgcontent,$userid=null){
        
        $ticket = Tickets::withTrashed()->find($ticketid);

        $subscribers = $ticket->subscribers();
        $reqEmail =$ticket->requestor()->email;
        $groupid = $ticket->requestor()->groupid;
        
        if($groupid == 4 && $ticket->requestor()->userid != $userid){
            $this->to[] = $reqEmail;
            $this->links[] = url("users/ticket")."/".encrypt($ticketid)."/".encrypt($reqEmail);
        }
        else if($ticket->requestor()->userid != $userid && $groupid != null){
            $this->to[] = $reqEmail;
            $pre = ($groupid ==6)? 'supervisors': 'admins';
            $this->links = array(url("$pre/ticket/$ticketid"));
        }
        
        foreach($subscribers as $subscriber){
            echo "initiator: $userid and recepient: ".$subscriber->user()->userid."<br/>";
            if($subscriber->user()->groupid ==4 && $subscriber->user()->userid != $userid){
                
                array_push($this->to, $subscriber->user()->email);
                array_push($this->links,url("users/ticket")."/".encrypt($ticketid)."/".encrypt($subscriber->user()->email));
            }
            
        }

        $this->buildEmailContent($ticketid,$msgcontent);
    }
    
    public function sendEmailToAll($ticketid,$msgcontent,$userid=null){
        $this->sendEmailToSubscribers($ticketid, $msgcontent, $userid);
        $this->sendEmailToOwner_Subscribers($ticketid, $msgcontent, $userid);
    }
    
    /**
     * 
     * @param int $ticketid
     * @param string $msgcontent
     */
    public function sendEmailToOwner_Subscribers($ticketid,$msgcontent,$userid=null){
        
        $ticket = Tickets::withTrashed()->find($ticketid);
        
        $subscribers = $ticket->subscribers();
        
        if($ticket->owner() != null && $ticket->owner()->userid != $userid){
            $pre = ($ticket->owner()->groupid ==6)? 'supervisors': 'admins';
            $this->links[] = url("$pre/ticket/$ticketid");
            $this->to[] = $ticket->owner()->email;
        }
        
        foreach($subscribers as $subscriber){
            
            $tempuser = $subscriber->user();
            
            if($tempuser->groupid !=4 && $tempuser->userid != $userid && $tempuser->groupid != null ){
                array_push($this->to, $tempuser->email);
                $pre = ($tempuser->groupid ==6)? 'supervisors': 'admins';
                array_push($this->links,url("$pre/ticket/$ticketid"));
            }
        }
        $this->buildEmailContent($ticketid,$msgcontent);
    }
    /**
     * 
     * @param int $ticketid
     * @param string $msgcontent
     */
    public function sendEmailToOwner($ticketid,$msgcontent,$userid=null){
        
        $ticket = Tickets::withTrashed()->find($ticketid);
        
        if($ticket->owner != null && $ticket->owner != $userid){
            $pre = ($ticket->owner()->groupid ==6)? 'supervisors': 'admins';
            $this->links[] = url("$pre/ticket/$ticketid");
            $this->to[] = $ticket->owner()->email;
            $this->buildEmailContent($ticketid,$msgcontent);
        }
    }
    
    /**
     * 
     * @param int $ticketid
     * @param string $msgcontent
     */
    public function sendEmailToRequestor($ticketid,$msgcontent,$userid=null){
        
        $ticket = Tickets::withTrashed()->find($ticketid);

        if($ticket->requestor != null && $ticket->requestor != $userid){
            $this->to[] = $ticket->requestor()->email;

            if($ticket->requestor()->groupid == 4){
                $this->links[] = url('users/ticket/'.encrypt($ticket->ticketid).'/'.encrypt($ticket->requestor()->email));
            }
            else{
                $pre = ($ticket->requestor()->groupid ==6)? 'supervisors': 'admins';
                $this->links[] = url("$pre/ticket/$ticketid");
            }
            
            $this->buildEmailContent($ticketid,$msgcontent);
        }
    }
    
    /**
     * 
     * @param int $ticketid
     * @param string $msgcontent
     */
    public function sendEmailToQA($ticketid,$msgcontent,$userid=null){
        
        $ticket = Tickets::withTrashed()->find($ticketid);
        
        if($ticket->qaowner() != null && $ticket->qaowner()->userid != $userid){
            $pre = ($ticket->qaowner()->groupid ==6)? 'supervisors': 'admins';
            $this->links[] = url("$pre/ticket/$ticketid");
            $this->to[] = $ticket->qaowner()->email;
            $this->buildEmailContent($ticketid,$msgcontent);
        }
    }
    
    /**
     * 
     * @param int $ticketid
     * @param string $msgcontent
     */
    public function sendEmailToQAStaff($ticketid,$msgcontent,$userid=null){
        
        $qas = User::where('groupid',5)->where('userid','!=',$userid)->get();
        
        foreach($qas as $qa){
            array_push($this->to,$qa->email);
            array_push($this->links,url("admins/ticket/$ticketid"));
        }
        $this->buildEmailContent($ticketid,$msgcontent);
    }
    
    /**
     * 
     * @param int $ticketid
     * @param string $msgcontent
     * @param User object $user
     */
    public function sendEmailToSpecificUser($ticketid,$msgcontent,$user,$userid=null){
        
        if($user->groupid == 4 && $user->userid != $userid){
            $this->to[] = $user->email;
            $this->links[] = url("users/ticket")."/".encrypt($ticketid)."/".encrypt($user->email);
        }
        elseif($user->groupid != 4 && $user->userid != $userid){
            $this->to[] = $user->email;
            $pre = ($user->groupid == 6)? 'supervisors':'admins';
            $this->links[] = url("$pre/ticket/$ticketid");
        }
        $this->buildSpecificEmailContent($ticketid,$msgcontent);
    }
    
    public function sendEmailToUser($msgcontent,$user,$type=1,$tofield='email'){
        if($user != null && isset($user->$tofield) && $user->$tofield != null){
            $this->type = $type;
            $this->from = (isset($msgcontent['from']))? $msgcontent['from'] : $this->from;
            array_push($this->to,$user->$tofield);
            $subject = $msgcontent['subject'];
            unset($msgcontent['subject']);
            $this->buildGenericContent($msgcontent,$subject);
        }
    }
    /**
     * 
     * @param int $ticketid
     * @param string $msgcontent
     */
    public function buildEmailContent($ticketid,$msgcontent){
        
        $ticket = Tickets::withTrashed()->find($ticketid);
        $this->subject = "Ticket ID: $ticketid - $ticket->title";
        
        foreach($this->to as $key=>$to){
            $a= $msgcontent;
            $a['link'] = $this->links[$key];
            array_push($this->body,json_encode(['body'=>$a]));
        }
    }
    
    public function buildSpecificEmailContent($ticketid,$msgcontent){
        
        $ticket = Tickets::withTrashed()->find($ticketid);
        $this->subject = "Ticket ID: $ticketid - $ticket->title";
        
        foreach($this->to as $key=>$to){
            $a= $msgcontent;
            $a['link'] = $this->links[$key];
            $this->body= array(json_encode(['body'=>$a]));
        }
    }
    
    public function buildGenericContent($msgcontent,$subject){
        
        $this->subject = $subject;
        $a= $msgcontent;
        $a['link'] = '';
        array_push($this->body,json_encode(['body'=>$a]));
        
    }
    
    /**
     * 
     * @param int $tickets
     * @param int $period
     * @param User object $user
     */
    public function buildRequestedTickets($tickets,$subscribedtickets,$period,$user){
        
        $this->subject = "Requested Tickets";
        
        $this->to = array($user->email);
        
        $c = "<h4>Requested Tickets from <span style='color:#0029A6'>$period[0] to $period[1]</span></h4><br/>";
        
        $a['title'] = "Requested Tickets from $period[0] to $period[1]";
        $a['content'] = $this->buildTickets($user, $tickets, $subscribedtickets);

        $a['link']='';

        array_push($this->body,json_encode(['body'=>$a]));
    }
    
    private function buildTickets($user,$tickets,$subscribedtickets){
        $color = '';
        $style = ($color=='#F5F7FA')? '#fff': '#F5F7FA';
        
        $table="<table style='float:left;width:100%;'><tr style='background-color:#E9573F;color:#fff;font-size:16px;line-height:30px;padding:5px;'><th>ID</th><th>Title</th><th>Status</th><th>LastUpdated</th></tr>"
                . "<tr style='background-color:$style;line-height:30px;padding:5px;'><td colspan='4' style='font-size:14px;font-weight:bold;color:#999;text-align:center;'>Requested Tickets</td></tr>"
                . "<tr></tr>";
        
        $table .= $this->buildRows($user, $tickets);
        
        $style= ($style=='#F5F7FA')? '#fff': '#F5F7FA';
        
        $table .= "<tr style='background-color:$style;line-height:30px;padding:5px;'><td colspan='4' style='font-size:14px;font-weight:bold;color:#999;text-align:center;'>Subscribed Tickets</td></tr>";
        $table .= "<tr></tr>";
        $table .= $this->buildRows($user, $subscribedtickets)."</table>";
        
        return $table;
    }
    
    private function buildRows($user,$tickets){
        
        $style='';
        $html ='';
        
        foreach ($tickets as $ticket){
            $style= ($style=='#F5F7FA')? '#fff': '#F5F7FA';
            $link = url("users/ticket")."/".encrypt($ticket->ticketid)."/".encrypt($user->email);
            $status = $ticket->status()->name;
            $last = formatDate(2,$ticket->updated_at);
            
            $html .= "<tr style='background-color:$style;color:#999;font-size:12px;line-height:30px;padding:5px;'><td>$ticket->ticketid</td><td><a href='$link'>$ticket->title</a></td><td>$status</td><td>$last</td></tr>"
                    . "<tr></tr>";
        }
        
        return $html;
    }

    /**
     * send email
     */
    public function sendEmail(){
        
        foreach($this->to as $key=>$user){
            \Log::info('sending email to '.$user);
            $body = json_decode($this->body[$key],true);
            
            //use this in production
            \Mail::send('emails.email',$body,function($message)use($user){
                $message->to($user)->subject($this->subject);
            });
            
            //use this for local environment
            $mail = new MailGunClient();

            $text = view()->make('emails.email')->with(['body'=>$body['body']]);

            $mail->sendEmail($this->from,$user, $this->subject, $text->render());
            
        }
    }
    
    public function sendEmailAsText(){
        foreach($this->to as $key=>$user){
            $body = json_decode($this->body[$key],true);
            \Mail::send('emails.textmessage',$body,function($message)use($user){
                $message->to($user)->subject('AAA Northeast Web Services');
            });
        }
    }
    
}
