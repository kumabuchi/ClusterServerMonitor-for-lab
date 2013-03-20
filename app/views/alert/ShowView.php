<?php
header("Content-Type: application/json; charset=utf-8");
print(json_encode(array('status' => $this->get('status'), 'data' => $this->get('data'))));
