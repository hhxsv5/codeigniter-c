<?php

/**
 * 扩展输出类
 * 支持直接输出json
 * @author Dave Xie <hhxsv5@sina.com>
 */
class MY_Output extends CI_Output
{

    /**
     * 输出json数据.
     *
     * @param mixed $data
     *            输出的数据
     * @param boolean $exit
     *            输出后立即退出
     * @return MY_Output
     */
    public function output_json($data, $exit = false)
    {
        $this->set_content_type('application/json');
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->set_output($data);
        if ($exit) {
            $this->_display();
            exit();
        }
        return $this;
    }
}