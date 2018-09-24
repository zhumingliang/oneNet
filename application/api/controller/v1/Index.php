<?php

namespace app\api\controller\v1;

class Index
{
    public function index()
    {
        $a = 1;
        $b = $a + 1;
        echo $b;
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
