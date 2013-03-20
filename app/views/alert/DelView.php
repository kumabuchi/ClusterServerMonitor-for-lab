<?php
header("Content-Type: application/json; charset=utf-8");
print(json_encode($this->get('json')));
