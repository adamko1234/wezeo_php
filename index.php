    <?php
    date_default_timezone_set("Europe/Bratislava");

    if (isset($_POST["name"]))
    {
        $name = $_POST["name"];
        $jsonTime = arrivals();
        $jsonStudent = students();
        index($name, $jsonTime, $jsonStudent);
        echo note($jsonStudent, $jsonTime);
    }
    function arrivals()
    {
        $arrivals = file_get_contents("prichody.json");
        return json_decode($arrivals, true);
    }

    function students()
    {
        $students = file_get_contents("studenti.json");
        return json_decode($students, true);
    }

    function index($name, &$jsonTime, &$jsonStudent)
    {
        $time = date("H:i");
        if (empty($jsonStudent))
        {
            $jsonStudent = [];
            array_push($jsonStudent, $name);
        }
        if (empty($jsonTime))
        {
            $jsonTime = [];
        array_push($jsonTime, $time);
        }

        saveData($jsonTime, $jsonStudent);
    }

    function saveData($jsonTime, $jsonStudent) {
        file_put_contents("prichody.json", json_encode($jsonTime));
        file_put_contents("studenti.json", json_encode($jsonStudent));
    }

    function note($jsonStudent, $jsonTime)
    {
        $notes = "";
        foreach ($jsonStudent as $index => $student) {
            if(isset($jsonTime[$index])){
                $time = $jsonTime[$index];
                if ($time <= "07:59") {
                    $notes .= "Prichod: " . $time . " => " . $student . "<br> ";
                }
                if ("08:00" <= $time & $time <= "20:00") {
                    $notes .= "Meskanie: " . $time . " => " . $student . "<br> ";
                }
                if ($time > "20" && $time <= "24") {
                    die($_POST['name'] . " Nemozne ");
                }
            }
        }
        return $notes;
    }