<?php

class SignupController extends ControllerBase{

    protected function form(){
        if( $this->user != null ){
            header("Location: " .$this->ini['site']['url']);
            exit;
        }
        $rand  = sha1(uniqid(mt_rand(), true));
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION['rand']  = $rand;
        $_SESSION['token'] = $token;
        $this->set('rand', $rand);
        $this->set('token', $token);
        $this->set('title', 'SMS SIGN UP');
        $this->set('url', $this->ini['site']['url']);
    }

    protected function setting(){
        if( $this->user == null ){
            header("Location: " .$this->ini['site']['url']);
            exit;
        }
        if( $this->lan == false ){
            header("Location: " .$this->ini['site']['url']."/signup/forbidden");
            exit;
        }
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION['token'] = $token;
        $this->set('token', $token);
        $this->set('title', 'SMS USER SETTING');
        $this->set('url', $this->ini['site']['url']);
    }

    protected function authenticate(){
        if( $this->user != null ){
            $this->set('status', 'ALREADY_SIGNUP');
            writeLog(1, 'ALREADY SIGNUP USER AUTHENTICATION : ' .$this->user. ' from ' .getHostAddr());
            return;
        }
        $token = $this->request->getPost('token');
        if( $_SESSION['token'] != $token ){
            $this->set('status', 'INVALID_REQUEST');
            writeLog(1, 'INVALID TOKEN REQUEST AUTHENTICATION : ' .getHostAddr());
            return;
        }
        $username = $this->request->getPost('user');
        //$username = strtolower($username);
        $password = $this->request->getPost('password');
        $registpass = $this->model->getPassword($username);
        $hashed = hash('sha512', $registpass.$_SESSION['rand']);
        if( $hashed != $password ){
            $this->set('status', $registpass.' : '.$password);
            writeLog(1, 'INCORRECT PASSWORD AUTHENTICATION : ' .$username. ' from ' .getHostAddr());
            return;
        }
        $this->set('status', 'OK');
        $_SESSION['user'] = $username;
    }

    protected function pass(){
        if( $this->user == null ){
            $this->set('status', 'UNSIGNUP_USER');
            writeLog(1, 'UNSIGNUP USER SET PASSWORD : from ' .getHostAddr());
            return;
        }
        $token = $this->request->getPost('token');
        if( $_SESSION['token'] != $token ){
            $this->set('status', 'INVALID_REQUEST');
            writeLog(1, 'INVALID TOKEN REQUEST AUTHENTICATION : ' .getHostAddr());
            return;
        }
        $old_password = $this->request->getPost('oldpass');
        $new_password = $this->request->getPost('newpass');

        $registpass = $this->model->getPassword($this->user);
        if( $registpass != null && $registpass != $old_password ){
            $this->set('status', 'NO_MATCH_OLD_PASSWORD');
            writeLog(1, 'NO MATCH OLD PASSWORD : ' .$this->user. ' from ' .getHostAddr());
            return;
        }
        $this->model->setPassword($this->user, $new_password);
        $this->set('status', 'OK');
    }

    protected function signout(){
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, $this->config['SESSION']['path']);
        } 
        session_destroy();
        header("Location: " .$this->ini['site']['url']);
    }

    protected function forbidden(){
        if( $this->lan == true ){
            header("Location: " .$this->ini['site']['url']);
            exit;
        }
        $this->set('url', $this->ini['site']['url']);
    }

    protected function edit(){
        if( $this->user == null ){
            header("Location: " .$this->ini['site']['url']);
            exit;
        }
        /*
        if( $this->lan == false ){
            header("Location: " .$this->ini['site']['url']."/signup/forbidden");
            exit;
        }
         */
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION['token'] = $token;
        $this->set('url', $this->ini['site']['url']);
        $this->set('user', $this->user);
        $this->set('email', $this->ini['mail'][$this->user]);
        $this->set('token', $token);
    }

    protected function editajax(){
        if( $this->user == null ){
            $this->set('status', 'UNSIGNUP_USER');
            writeLog(1, 'UNSIGNUP USER EDIT INFORMATION : from ' .getHostAddr());
            return;
        }
        $token = $this->request->getPost('token');
        if( $_SESSION['token'] != $token ){
            $this->set('status', 'INVALID_REQUEST');
            writeLog(1, 'INVALID TOKEN REQUEST EDIT INFORMATION : ' .getHostAddr());
            return;
        }
        if( !isset($this->ini['mail'][$this->user]) ){
            $this->set('status', 'NO_USER_EMAIL');
            writeLog(1, 'NO USER EMAIL : ' .getHostAddr());
            return;
        }
        $user  = $this->request->getPost('user');
        $email = $this->request->getPost('email');
        $subject = "[EDIT REQUEST] USER INFORMATION";
        $body = "There is USER INFORMATION EDIT REQUEST.\n".
            "REQUEST USER : ".$this->user."\n";
        if( $user != "" ){
            $body.= "[USER] \t".$user."\n";
        }
        if( $email != "" ){
            $body.= "[EMAIL]\t".$email."\n";
        }
        sendMail($this->ini['site']['adminmail'],$subject,$body); 
        $this->set('status', 'OK');
    }

    protected function alert(){
        if( $this->user == null ){
            header("Location: " .$this->ini['site']['url']);
            exit;
        }
        /*
        if( $this->lan == false ){
            header("Location: " .$this->ini['site']['url']."/signup/forbidden");
            exit;
        }
         */
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION['token'] = $token;
        $this->set('url', $this->ini['site']['url']);
        $this->set('user', $this->user);
        $this->set('email', $this->ini['mail'][$this->user]);
        $this->set('token', $token);
    }

    protected function alertajax(){
        if( $this->user == null ){
            $this->set('status', 'UNSIGNUP_USER');
            writeLog(1, 'UNSIGNUP USER SET SERVER ALERT : from ' .getHostAddr());
            return;
        }
        $token = $this->request->getPost('token');
        if( $_SESSION['token'] != $token ){
            $this->set('status', 'INVALID_REQUEST');
            writeLog(1, 'INVALID TOKEN REQUEST SERVER ALERT : ' .getHostAddr());
            return;
        }
        if( !isset($this->ini['mail'][$this->user]) ){
            $this->set('status', 'NO_USER_EMAIL');
            writeLog(1, 'NO USER EMAIL : ' .getHostAddr());
            return;
        }
        $user  = $this->request->getPost('user');
        $email = $this->request->getPost('email');
        $subject = "[ALERT REQUEST] SERVER DOWN ALERT SYSTEM";
        $body = "There is SERVER DOWN ALERT SYSTEM REGISTRATION REQUEST.\n".
                "REQUEST USER : ".$this->user."\n";
        $body.= "[USER] \t".$user."\n";
        $body.= "[EMAIL]\t".$email."\n";
        sendMail($this->ini['site']['adminmail'],$subject,$body); 
        $this->set('status', 'OK');
    }

}









