<?php
// --- SAFE SIMULATION MODE ---
// This script checks your input for specific keywords instead of running real commands.

$output = "";
$cmd_color = "#555";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target = $_POST['target'];
    
    // Simulate the time delay of a real ping
    sleep(1);

    // LOGIC: We check what the user typed to decide what to show.
    
    // 1. If they try to list files (ls)
    if (strpos($target, 'ls') !== false) {
        $output = "PING 127.0.0.1 (127.0.0.1) 56(84) bytes of data.\n" .
                  "64 bytes from 127.0.0.1: icmp_seq=1 ttl=64 time=0.042 ms\n" .
                  "--- 127.0.0.1 ping statistics ---\n" .
                  "1 packets transmitted, 1 received, 0% packet loss\n\n" .
                  "index.php\nstyle.css\nsecret_flag_x99.txt  <-- [LOOK!]\nimages/";
        $cmd_color = "#00ff41";
    }
    // 2. If they try to read the flag (cat)
    elseif (strpos($target, 'cat') !== false && strpos($target, 'secret_flag_x99.txt') !== false) {
        $output = "CRITICAL ALERT: ROOT ACCESS GRANTED.\n" .
                  "----------------------------------------\n" .
                  "FLAG: CTF{comm4nd_inj3ct1on_m4st3r}\n" .
                  "----------------------------------------";
        $cmd_color = "#00ff41";
    }
    // 3. Normal Ping (Default behavior)
    else {
        // We just show a fake ping result for whatever IP they typed
        $safe_ip = htmlspecialchars(explode(";", $target)[0]); // Clean the input
        $output = "PING $safe_ip ($safe_ip) 56(84) bytes of data.\n" .
                  "64 bytes from $safe_ip: icmp_seq=1 ttl=64 time=0.32 ms\n" .
                  "64 bytes from $safe_ip: icmp_seq=2 ttl=64 time=0.41 ms\n" .
                  "--- $safe_ip ping statistics ---\n" .
                  "2 packets transmitted, 2 received, 0% packet loss";
        $cmd_color = "#555";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NET_DIAGNOSTICS // v0.9 (SAFE MODE)</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=VT323&display=swap');
        
        body {
            background-color: #101010;
            color: #ccc;
            font-family: 'VT323', monospace;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        /* Scanline effect */
        body::after {
            content: " "; display: block; position: absolute; top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            z-index: 2; background-size: 100% 2px, 3px 100%; pointer-events: none;
        }

        .terminal {
            width: 700px;
            height: 500px;
            background: #000;
            border: 2px solid #333;
            padding: 20px;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.9);
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        .header {
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #666;
            display: flex;
            justify-content: space-between;
        }

        .screen {
            flex-grow: 1;
            background: #050505;
            padding: 15px;
            border: 1px solid #222;
            color: <?php echo $cmd_color; ?>;
            overflow-y: auto;
            white-space: pre-wrap; 
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .input-line {
            display: flex;
            align-items: center;
            border-top: 1px solid #333;
            padding-top: 20px;
        }

        .prompt { color: #00ff41; margin-right: 10px; }

        input {
            background: transparent;
            border: none;
            color: #fff;
            font-family: 'VT323', monospace;
            font-size: 1.3rem;
            flex-grow: 1;
            outline: none;
        }

        button {
            background: #222;
            border: 1px solid #444;
            color: #fff;
            font-family: 'VT323', monospace;
            font-size: 1.2rem;
            padding: 5px 20px;
            cursor: pointer;
        }
        button:hover { background: #00ff41; color: #000; }

    </style>
</head>
<body>

    <div class="terminal">
        <div class="header">
            <span>SYSTEM_DIAGNOSTICS_TOOL</span>
            <span>ROOT: SIMULATION_MODE</span>
        </div>

        <div class="screen">
<?php 
if ($output) {
    echo $output;
} else {
    echo "WAITING FOR INPUT...\nENTER TARGET IP ADDRESS FOR CONNECTIVITY CHECK.";
}
?>
        </div>

        <form method="POST" class="input-line">
            <span class="prompt">admin@server:~$ ping -c 2</span>
            <input type="text" name="target" placeholder="127.0.0.1" autocomplete="off" autofocus>
            <button type="submit">EXECUTE</button>
        </form>
    </div>

</body>
</html>
