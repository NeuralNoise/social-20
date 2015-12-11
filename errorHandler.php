<HTML>
<HEAD>
</HEAD>
<BODY background='gray'>


<?php

class ErrorHandler
{
    public function __construct($msg)
    {
        echo "The following errors were found out in the code. Sort them out soon: <br/>" . $msg;
    }
}

?>
</BODY>
</HTML>