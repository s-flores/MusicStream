<?php


class Account
{
    private $con;
    private $errorArray;

    public function __construct($con){
        $this->con = $con;
        $this->errorArray = array();
    }
    public function register($un,$fn,$ln,$em,$em2,$pw,$pw2){
        $this->validateUsername($un);
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateEmails($em,$em2);
        $this->validatePasswords($pw,$pw2);

        if(empty($this->errorArray)){
            //Insert into DB
            return $this->insertUserDetails($un,$fn,$ln,$em,$pw);
        }
        else{
            return false;
        }
    }

    public function getError($error){
        if(!in_array($error, $this->errorArray)){
            $error = "";
        }
        return "<span class='errorMessage'>$error</span>";
    }
    private function insertUserDetails($un,$fn,$ln,$em,$pw){
        $encryptedPw = md5($pw);
        $profilePic = "assets/images/profile-pics.jpg/no-image.jpg";
        $date = date("Y-m-d");

        $sql ="INSERT INTO users VALUES('','$un','$fn','$ln','$em','$encryptedPw','$date','$profilePic')";
        //$result = mysqli_query($this->con, "INSERT INTO users VALUES('','$un','$fn','$ln','$em','$encryptedPw', '$date', '$profilePic')");
        return $result = mysqli_query($this->con, $sql);
    }

    private function validateUsername($un){
        if(strlen($un) > 25 || strlen($un) < 5){
            array_push($this->errorArray, Constants::usernameCharacters);
            return;
        }
        // TODO: check if username exist
    }
    private function validateFirstName($fn){
        if(strlen($fn) > 25 || strlen($fn) < 2){
            array_push($this->errorArray, Constants::firstNameCharacters);
            return;
        }
    }
    private function validateLastName($ln){
        if(strlen($ln) > 25 || strlen($ln) < 2){
            array_push($this->errorArray, Constants::lastNameCharacters);
            return;
        }
    }
    private function validateEmails($em, $em2){
        if($em != $em2){
            array_push($this->errorArray, Constants::emailNotMatch);
            return;
        }
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray, Constants::emailInvalid);
            return;
        }
        //TODO: check that username is not taken
    }
    private  function validatePasswords($pw, $pw2){
        if($pw != $pw2){
            array_push($this->errorArray, Constants::passwordsNotMatch);
            return;
        }
        if(preg_match('/[^A-Za-z0-9]/', $pw)){
            array_push($this->errorArray, Constants::passwordsNotAlphanumeric);
            return;
        }
        if(strlen($pw) > 20 || strlen($pw) < 5){
            array_push($this->errorArray, Constants::passwordCharacters);
            return;
        }


    }
}
?>