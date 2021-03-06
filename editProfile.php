<?php include_once "pages/head.php"; ?>
<?php
$sql = new SQL();
$errors = [];

if(is_post())
{
    $suggestions = $sql->execute("SELECT * FROM `shippings` WHERE `id` = ?",$_SESSION['user_shippingID']);
    
    if(isset($_POST['username'])) $userName = $_POST['username'];
    if(isset($_POST['email']))$email = $_POST['email'];
    if(isset($_POST['fullname']))$fullName = $_POST['fullname'];

    
    if(isset($_POST['recipient']))$recipient = $_POST['recipient']; if($_POST['recipient'] == null) $recipient ="none";
    if(isset($_POST['country']))$country = $_POST['country']; if($_POST['country'] == null) $country ="none";
    if(isset($_POST['city']))$city = $_POST['city']; if($_POST['city'] == null) $city ="none";
    if(isset($_POST['adress']))$adress = $_POST['adress']; if($_POST['adress'] == null) $adress ="none";
    if(isset($_POST['tel']))$tel = $_POST['tel']; if($_POST['tel'] == null) $tel ="none";
    if(isset($_POST['cemail']))$cemail = $_POST['cemail']; if($_POST['cemail'] == null) $cemail ="none";

    

    //email
    if(isset($email)){
        if ($email == null) $errors['email'][] = 'Email is required!';
        else if(!(preg_match("/^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/",$email))) $errors['email'][] = 'Invalid email!';
            $emails = $sql->execute("SELECT `email` FROM users");
                foreach($emails as $row) 
                    if($row['email'] == $email && $email != $_SESSION['user_email'])
                     $errors['email'][] = 'Email is already taken!';
    }
    

    //userName 
    if(isset($userName)){
        if($userName == null) $errors['userName'][] = 'User name is required!';
        else 
        {
            if(strpos($userName,"@")) $errors['userName'][] = 'User name can not contain "@" character!';
            if(strlen($userName) < 6) $errors['userName'][] = 'User name has to be at least 6 characters long!';
            else if(strlen($userName) > 25) $errors['userName'][] = 'User name can not be longer than 25 characters!';
            $userNames = $sql->execute("SELECT `user_name` FROM users");
            foreach($userNames as $row)
                if($row['user_name'] == $userName && $userName != $_SESSION['user_user_name'])
                    $errors['userName'][] = 'User name is already taken!';
        }
    }
    
    //full name
    if(isset($fullName)){
        if($fullName == null) $errors['fullName'][] = 'Full name is required!';
        else if(strlen($fullName) < 4) $errors['fullName'][] = 'full name is too short!';
        else if(strlen($fullName) > 255) $errors['fullName'][] = 'full name is too long!';
    }
    

    //pic
    $allow = array("jpg", "jpeg", "png");
    $dir = "images\\profilepic\\";
    if(isset($_FILES['pic']['name']) && $_FILES['pic']['name'] != null)
    {
        $pic = $_FILES['pic']['name'];
        $extension = explode(".", $pic);
        $extension = end($extension);
        $fullpath = $dir.$pic;
        if(in_array($extension, $allow) && !file_exists($fullpath))
            move_uploaded_file($_FILES['pic']['tmp_name'], $fullpath);
        else if(in_array($extension, $allow) && file_exists($fullpath)){
            $fullpath = $dir.GenerateID().$pic;
            move_uploaded_file($_FILES['pic']['tmp_name'], $fullpath);
        }
        $sql->execute("UPDATE `users` SET `profile_pic` = ? WHERE `id` = ?",$fullpath, $_SESSION['user_id']);
    }

    if(isset($recipient)){
        if($recipient == null) $errors['recipient'][] = "Name of recipient is required!";
    }

    //country
    if(isset($country)){
        if($country == null) $errors['country'][] = "Please choose a country!";
    }

     //city
     if(isset($city)){
        if($city == null) $errors['city'][] = "Please choose a country!";
    }

     //adress
     if(isset($adress)){
        if($adress == null) $errors['adress'][] = "Please choose a country!";
    }
    
    //Set changes
    if(count($errors) == 0) {
        if(isset($userName) && $userName != $_SESSION['user_user_name']){
            $sql->execute("UPDATE `users` SET `user_name` = ? WHERE `id` = ?",$userName,$_SESSION['user_id']);
            $_SESSION['user_user_name'] = $userName;
        }
           
        if(isset($email) && $email != $_SESSION['user_email']){
            $sql->execute("UPDATE `users` SET `email` = ? WHERE `id` = ?",$email,$_SESSION['user_id']);
            $_SESSION['user_email'] = $email;
        }
            
        if(isset($fullName) && $fullName != $_SESSION['user_fullname']){
            $sql->execute("UPDATE `users` SET `fullname` = ? WHERE `id` = ?",$fullName,$_SESSION['user_id']);
            $_SESSION['user_fullname'] = $fullName;
        }
            if(isset($suggestions[0]['id']))
            $sql->execute("UPDATE `shippings` SET `country`=?,`client_name`=?,`city`=?,`address`=?,`tel`=?,`email`=? WHERE `id` = ?",$country,$recipient,$city,$adress,$tel,$cemail,$_SESSION['user_shippingID']);
            else
            $sql->execute("INSERT INTO `shippings`(`id`, `country`, `client_name`, `city`, `address`, `tel`, `email`) VALUES (?,?,?,?,?,?,?)",$_SESSION['user_shippingID'],$country,$recipient,$city,$adress,$adress,$tel,$cemail);

            $URL="http://localhost:8080/blank_team/?p=profile";
            echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";  
    }
}
$suggestions = $sql->execute("SELECT * FROM `shippings` WHERE `id` = ?",$_SESSION['user_shippingID']);

  if(isset($_SESSION['user_id']))
  $profilepic = $sql->execute("SELECT `profile_pic` FROM `users` WHERE id = ?",$_SESSION['user_id']);
  else
  $profilepic = "images\\profilepic\\user.jpg";
?>
<div class="edit-profile-container">
    <h1 class="login-h">EDIT PROFILE PAGE</h1>  
    <div class="e-p-right-side">
        <img id = "pic" src = "<?php echo isset($_SESSION['user_id']) ? $profilepic[0]['profile_pic'] : $profilepic ?>" alt = "profile picture">
        <form action="<?php echo url('editProfile')?>" method ="POST" enctype="multipart/form-data" autocomplete="off">
        <input type ="file" name ="pic" id ="pic" onchange="loadFile(event)" accept="image/png, image/jpeg, image/jpg" /> <br>

    </div>
    <div class="e-p-left-side"> 
            <h1>Personal data</h1>
            <label for="username">User Name</label><br>
            <input type="text" name = "username" value="<?php echo $_SESSION['user_user_name'] ?>"><br>
            <?php if(isset($errors['userName'])) foreach ($errors['userName'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 

            <label for="email">Email</label><br>
            <input type="text" name = "email" value="<?php echo $_SESSION['user_email'] ?>"><br>
            <?php if(isset($errors['email'])) foreach ($errors['email'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 

            <label for="fullname">Full Name</label><br>
            <input type="text" name = "fullname" value="<?php echo $_SESSION['user_fullname'] ?>"><br>
            <?php if(isset($errors['fullName'])) foreach ($errors['fullName'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 

            <p class="p-e-change-pw">Do you want to change your passsword?<a href="<?php echo url('forgottenPassword') ?>">...Click here!</a></p>

            <h1>Shipping data</h1>
            <label for="recipient">Name of recipient</label><br>
            <input type="text" name = "recipient" value="<?php echo isset($suggestions[0]['client_name']) && $suggestions[0]['client_name'] != "none"  ? $suggestions[0]['client_name'] : "" ?>"><br>
            <?php if(isset($errors['recipient'])) foreach ($errors['recipient'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 

            <label for="country">Country</label> <br>
              <!--#region county  -->
                    <select class="country" name="country">
                    <option 
                            value="<?php echo isset($suggestions[0]['country']) ? $suggestions[0]['country'] : "" ?>" 
                            <?php echo isset($suggestions[0]['country']) ? "SELECTED" :"" ?>>
                            <?php echo isset($suggestions[0]['country']) ? $suggestions[0]['country'] : "" ?>
                        </option>
                        <optgroup label="North America">
                            <option value="US">United States</option>
                            <option value="UM">United States Minor Outlying Islands</option>
                            <option value="CA">Canada</option>
                            <option value="MX">Mexico</option>
                            <option value="AI">Anguilla</option>
                            <option value="AG">Antigua and Barbuda</option>
                            <option value="AW">Aruba</option>
                            <option value="BS">Bahamas</option>
                            <option value="BB">Barbados</option>
                            <option value="BZ">Belize</option>
                            <option value="BM">Bermuda</option>
                            <option value="VG">British Virgin Islands</option>
                            <option value="KY">Cayman Islands</option>
                            <option value="CR">Costa Rica</option>
                            <option value="CU">Cuba</option>
                            <option value="DM">Dominica</option>
                            <option value="DO">Dominican Republic</option>
                            <option value="SV">El Salvador</option>
                            <option value="GD">Grenada</option>
                            <option value="GP">Guadeloupe</option>
                            <option value="GT">Guatemala</option>
                            <option value="HT">Haiti</option>
                            <option value="HN">Honduras</option>
                            <option value="JM">Jamaica</option>
                            <option value="MQ">Martinique</option>
                            <option value="MS">Montserrat</option>
                            <option value="AN">Netherlands Antilles</option>
                            <option value="NI">Nicaragua</option>
                            <option value="PA">Panama</option>
                            <option value="PR">Puerto Rico</option>
                            <option value="KN">Saint Kitts and Nevis</option>
                            <option value="LC">Saint Lucia</option>
                            <option value="VC">Saint Vincent and the Grenadines</option>
                            <option value="TT">Trinidad and Tobago</option>
                            <option value="TC">Turks and Caicos Islands</option>
                            <option value="VI">US Virgin Islands</option>
                        </optgroup>
                        <optgroup label="South America">
                            <option value="AR">Argentina</option>
                            <option value="BO">Bolivia</option>
                            <option value="BR">Brazil</option>
                            <option value="CL">Chile</option>
                            <option value="CO">Colombia</option>
                            <option value="EC">Ecuador</option>
                            <option value="FK">Falkland Islands (Malvinas)</option>
                            <option value="GF">French Guiana</option>
                            <option value="GY">Guyana</option>
                            <option value="PY">Paraguay</option>
                            <option value="PE">Peru</option>
                            <option value="SR">Suriname</option>
                            <option value="UY">Uruguay</option>
                            <option value="VE">Venezuela</option>
                        </optgroup>
                        <optgroup label="Europe">
                            <option value="GB">United Kingdom</option>
                            <option value="AL">Albania</option>
                            <option value="AD">Andorra</option>
                            <option value="AT">Austria</option>
                            <option value="BY">Belarus</option>
                            <option value="BE">Belgium</option>
                            <option value="BA">Bosnia and Herzegovina</option>
                            <option value="BG">Bulgaria</option>
                            <option value="HR">Croatia (Hrvatska)</option>
                            <option value="CY">Cyprus</option>
                            <option value="CZ">Czech Republic</option>
                            <option value="FR">France</option>
                            <option value="GI">Gibraltar</option>
                            <option value="DE">Germany</option>
                            <option value="GR">Greece</option>
                            <option value="VA">Holy See (Vatican City State)</option>
                            <option value="HU">Hungary</option>
                            <option value="IT">Italy</option>
                            <option value="LI">Liechtenstein</option>
                            <option value="LU">Luxembourg</option>
                            <option value="MK">Macedonia</option>
                            <option value="MT">Malta</option>
                            <option value="MD">Moldova</option>
                            <option value="MC">Monaco</option>
                            <option value="ME">Montenegro</option>
                            <option value="NL">Netherlands</option>
                            <option value="PL">Poland</option>
                            <option value="PT">Portugal</option>
                            <option value="RO">Romania</option>
                            <option value="SM">San Marino</option>
                            <option value="RS">Serbia</option>
                            <option value="SK">Slovakia</option>
                            <option value="SI">Slovenia</option>
                            <option value="ES">Spain</option>
                            <option value="UA">Ukraine</option>
                            <option value="DK">Denmark</option>
                            <option value="EE">Estonia</option>
                            <option value="FO">Faroe Islands</option>
                            <option value="FI">Finland</option>
                            <option value="GL">Greenland</option>
                            <option value="IS">Iceland</option>
                            <option value="IE">Ireland</option>
                            <option value="LV">Latvia</option>
                            <option value="LT">Lithuania</option>
                            <option value="NO">Norway</option>
                            <option value="SJ">Svalbard and Jan Mayen Islands</option>
                            <option value="SE">Sweden</option>
                            <option value="CH">Switzerland</option>
                            <option value="TR">Turkey</option>
                        </optgroup>
                        <optgroup label="Asia">
                            <option value="AF">Afghanistan</option>
                            <option value="AM">Armenia</option>
                            <option value="AZ">Azerbaijan</option>
                            <option value="BH">Bahrain</option>
                            <option value="BD">Bangladesh</option>
                            <option value="BT">Bhutan</option>
                            <option value="IO">British Indian Ocean Territory</option>
                            <option value="BN">Brunei Darussalam</option>
                            <option value="KH">Cambodia</option>
                            <option value="CN">China</option>
                            <option value="CX">Christmas Island</option>
                            <option value="CC">Cocos (Keeling) Islands</option>
                            <option value="GE">Georgia</option>
                            <option value="HK">Hong Kong</option>
                            <option value="IN">India</option>
                            <option value="ID">Indonesia</option>
                            <option value="IR">Iran</option>
                            <option value="IQ">Iraq</option>
                            <option value="IL">Israel</option>
                            <option value="JP">Japan</option>
                            <option value="JO">Jordan</option>
                            <option value="KZ">Kazakhstan</option>
                            <option value="KP">Korea, Democratic People's Republic of</option>
                            <option value="KR">Korea, Republic of</option>
                            <option value="KW">Kuwait</option>
                            <option value="KG">Kyrgyzstan</option>
                            <option value="LA">Lao</option>
                            <option value="LB">Lebanon</option>
                            <option value="MY">Malaysia</option>
                            <option value="MV">Maldives</option>
                            <option value="MN">Mongolia</option>
                            <option value="MM">Myanmar (Burma)</option>
                            <option value="NP">Nepal</option>
                            <option value="OM">Oman</option>
                            <option value="PK">Pakistan</option>
                            <option value="PH">Philippines</option>
                            <option value="QA">Qatar</option>
                            <option value="RU">Russian Federation</option>
                            <option value="SA">Saudi Arabia</option>
                            <option value="SG">Singapore</option>
                            <option value="LK">Sri Lanka</option>
                            <option value="SY">Syria</option>
                            <option value="TW">Taiwan</option>
                            <option value="TJ">Tajikistan</option>
                            <option value="TH">Thailand</option>
                            <option value="TP">East Timor</option>
                            <option value="TM">Turkmenistan</option>
                            <option value="AE">United Arab Emirates</option>
                            <option value="UZ">Uzbekistan</option>
                            <option value="VN">Vietnam</option>
                            <option value="YE">Yemen</option>
                        </optgroup>
                        <optgroup label="Australia / Oceania">
                            <option value="AS">American Samoa</option>
                            <option value="AU">Australia</option>
                            <option value="CK">Cook Islands</option>
                            <option value="FJ">Fiji</option>
                            <option value="PF">French Polynesia (Tahiti)</option>
                            <option value="GU">Guam</option>
                            <option value="KB">Kiribati</option>
                            <option value="MH">Marshall Islands</option>
                            <option value="FM">Micronesia, Federated States of</option>
                            <option value="NR">Nauru</option>
                            <option value="NC">New Caledonia</option>
                            <option value="NZ">New Zealand</option>
                            <option value="NU">Niue</option>
                            <option value="MP">Northern Mariana Islands</option>
                            <option value="PW">Palau</option>
                            <option value="PG">Papua New Guinea</option>
                            <option value="PN">Pitcairn</option>
                            <option value="WS">Samoa</option>
                            <option value="SB">Solomon Islands</option>
                            <option value="TK">Tokelau</option>
                            <option value="TO">Tonga</option>
                            <option value="TV">Tuvalu</option>
                            <option value="VU">Vanuatu</option>
                            <option valud="WF">Wallis and Futuna Islands</option>
                        </optgroup>
                        <optgroup label="Africa">
                            <option value="DZ">Algeria</option>
                            <option value="AO">Angola</option>
                            <option value="BJ">Benin</option>
                            <option value="BW">Botswana</option>
                            <option value="BF">Burkina Faso</option>
                            <option value="BI">Burundi</option>
                            <option value="CM">Cameroon</option>
                            <option value="CV">Cape Verde</option>
                            <option value="CF">Central African Republic</option>
                            <option value="TD">Chad</option>
                            <option value="KM">Comoros</option>
                            <option value="CG">Congo</option>
                            <option value="CD">Congo, the Democratic Republic of the</option>
                            <option value="DJ">Dijibouti</option>
                            <option value="EG">Egypt</option>
                            <option value="GQ">Equatorial Guinea</option>
                            <option value="ER">Eritrea</option>
                            <option value="ET">Ethiopia</option>
                            <option value="GA">Gabon</option>
                            <option value="GM">Gambia</option>
                            <option value="GH">Ghana</option>
                            <option value="GN">Guinea</option>
                            <option value="GW">Guinea-Bissau</option>
                            <option value="CI">Cote d'Ivoire (Ivory Coast)</option>
                            <option value="KE">Kenya</option>
                            <option value="LS">Lesotho</option>
                            <option value="LR">Liberia</option>
                            <option value="LY">Libya</option>
                            <option value="MG">Madagascar</option>
                            <option value="MW">Malawi</option>
                            <option value="ML">Mali</option>
                            <option value="MR">Mauritania</option>
                            <option value="MU">Mauritius</option>
                            <option value="YT">Mayotte</option>
                            <option value="MA">Morocco</option>
                            <option value="MZ">Mozambique</option>
                            <option value="NA">Namibia</option>
                            <option value="NE">Niger</option>
                            <option value="NG">Nigeria</option>
                            <option value="RE">Reunion</option>
                            <option value="RW">Rwanda</option>
                            <option value="ST">Sao Tome and Principe</option>
                            <option value="SH">Saint Helena</option>
                            <option value="SN">Senegal</option>
                            <option value="SC">Seychelles</option>
                            <option value="SL">Sierra Leone</option>
                            <option value="SO">Somalia</option>
                            <option value="ZA">South Africa</option>
                            <option value="SS">South Sudan</option>
                            <option value="SD">Sudan</option>
                            <option value="SZ">Swaziland</option>
                            <option value="TZ">Tanzania</option>
                            <option value="TG">Togo</option>
                            <option value="TN">Tunisia</option>
                            <option value="UG">Uganda</option>
                            <option value="EH">Western Sahara</option>
                            <option value="ZM">Zambia</option>
                            <option value="ZW">Zimbabwe</option>
                        </optgroup>
                        <option value="AQ">Antarctica</option>
                    </select>
        <!-- #endregion  -->
        <?php if(isset($errors['country'])) foreach ($errors['country'] as $value) echo "<p class ='input-error'> $value </p>"; ?> <br>
        
            <label for="city">City</label><br>
            <input type="text" name = "city" value="<?php echo isset($suggestions[0]['city']) && $suggestions[0]['city'] != "none"  ? $suggestions[0]['city'] : "" ?>"><br>
            <?php if(isset($errors['city'])) foreach ($errors['city'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 

            <label for="adress">Adress</label><br>
            <input type="text" name = "adress" value="<?php echo isset($suggestions[0]['address']) && $suggestions[0]['address'] != "none"  ? $suggestions[0]['address'] : "" ?>"><br>
            <?php if(isset($errors['adress'])) foreach ($errors['adress'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 

            <label for="tel">Telephone number</label><br>
            <input type="number" name = "tel" value="<?php echo isset($suggestions[0]['tel']) && $suggestions[0]['tel'] != "none"  ? $suggestions[0]['tel'] : "" ?>"><br>
            <?php if(isset($errors['tel'])) foreach ($errors['tel'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 

            <label for="cemail">Contact email</label><br>
            <input type="text" name = "cemail" value="<?php echo $_SESSION['user_email'] ?>"><br>
            <?php if(isset($errors['cemail'])) foreach ($errors['cemail'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 

            <button class ="edit-profile-btn" type="submit" value = "edit" name="letsedit"><Span>Save changes</span></button>

            </form>
        </div>
</div>
<script>
var loadFile = function(event) {
	var image = document.getElementById('pic');
	image.src = URL.createObjectURL(event.target.files[0]);
};
</script>
<?php include_once "pages/footer.php"; ?>