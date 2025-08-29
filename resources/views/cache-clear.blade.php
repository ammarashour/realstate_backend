<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>Repair Vue</title>
</head>
<body>
    <h1>Repair Cache Instruction</h1>
    <p>
        Step 1 : Please click "Repair Cache" button ( It will clear the page and auto reload the page )
        <br>
        Step 2 : After that you can go back to dashboard , Clicking "Go to Dashboard" button.
    </p>

    <br>
    <div>
        <button id="repairCache">Repaire Cache</button>
        <button id="toDashboard">Go To Dashboard</button>
    </div>
    <br>
    <hr>
    <p>
        If still no luck, try to do the followings.
    </p>
    {{-- <p>
        1. Try to use with difference browsers. <br>
        2. Clear the browser cache from setting. <br>
        3. Contact to PS Support
    </p> --}}
    <div>
        <p>
            Quick hard refresh can be done by using the following shortcut keys
        </p>

        <div>
            <h2>Chrome:</h2>
            <p><b>Windows/Linux:</b></p>
            1. Hold down Ctrl and click the Reload button <br>
            2. Or, Hold down Ctrl and press F5. <br>
            3. Or, Open the Chrome Dev Tools by pressing F12. Once the chrome dev tools are open, just right click on the refresh button and a menu will drop down. This menu gives you the option of doing a hard refresh, or even clearing the cache and do a hard refresh automatically. <br>

            <p><b>Mac:</b></p>
            1. Hold ⇧ Shift and click the Reload button. <br>
            2. Or, hold down ⌘ Cmd and ⇧ Shift key and then press R. <br>
            3. Or, Open the Chrome Dev Tools by pressing fn and F12 together. Once the chrome dev tools are open, just right click on the refresh button and a menu will drop down. This menu gives you the option of doing a hard refresh, or even clearing the cache and do a hard refresh automatically.
        </div>
        <br>

        <div>
            <h2> Mozilla Firefox and Related Browsers:</h2>
            <p><b>Windows/Linux:</b></p>
            1. Hold the Ctrl key and press the F5 key. <br>
            2. Or, hold down Ctrl and ⇧ Shift and then press R. <br>

            <p><b> Mac:</b></p>
            1. Hold down the ⇧ Shift and click the Reload button. <br>
            2. Or, hold down ⌘ Cmd and ⇧ Shift and then press R.
        </div>
        <br>

        <div>
            <h2> Internet Explorer:</h2>
            1. Hold the Ctrl key and press the F5 key. <br>
            2. Or, hold the Ctrl key and click the Refresh button.
        </div>
        <br>

        <div>
            <h2> Safari</h2>
            1. Hold the Control key, press the F5 key. <br>
            2. Or, hold the Control key, click the Refresh button.
        </div>
        <br>

    </div>

</body>
<script>

    const repairCache = document.getElementById("repairCache");
    const toDashboard = document.getElementById("toDashboard");

    repairCache.addEventListener("click", handleRepairCache);
    toDashboard.addEventListener("click", handleToDashboard);

    function handleRepairCache() {
        window.location.reload(true);
    }

    function handleToDashboard(){
        window.location.href = "{{ route('dashboard') }}";
    }

</script>
</html>
