<?php

function exception_handler($exception)
{
	FormatResponseToJSON(NULL, $exception -> getMessage(), $exception -> getCode());
}

set_exception_handler('exception_handler');