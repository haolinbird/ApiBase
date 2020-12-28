<?php
namespace Util;

/**
 *提示消息暂存.
 */
class MessageManager extends \Util\CInstance
{
    /**
     *成功的提示.
     */
    const TYPE_SUCCESS = 'success';

    /**
     *告警的提示.
     */
    const TYPE_WARNING = 'warning';

    /**
     *全部提示.
     */
    const TYPE_ALL = 'all';

    /**
     *错误的提示.
     */
    const TYPE_ERROR = 'error';
    /**
     *成功的提示.
     */
    const TYPE_INFO = 'info';

    /**
     *提示消息.
     *
     * @var array.
     */
    private $message = array();

    /**
     *添加提示.
     *
     * @param string $msg 提示信息.
     *
     * @param string $type 提示类型.
     *
     * @return $this.
     */
    protected function addSMessage($msg, $type)
    {
        $this->message[self::TYPE_ALL][] = $this->message[$type][] = array(
            'message' => $msg,
            'type' => $type,
        );
        return $this;
    }

    /**
     *错误提示.
     *
     * @param string $msg 提示语.
     *
     * @return $this.
     */
    public function addMessageError($msg)
    {
        $this->addSMessage($msg, self::TYPE_ERROR);
        return $this;
    }

    /**
     *警告提示.
     *
     * @param string $msg 提示语.
     *
     * @return $this.
     */
    public function addMessageWarning($msg)
    {
        $this->addSMessage($msg, self::TYPE_WARNING);
        return $this;
    }

    /**
     *成功提示.
     *
     * @param string $msg 提示语.
     *
     * @return $this.
     */
    public function addMessageSuccess($msg)
    {
        $this->addSMessage($msg, self::TYPE_SUCCESS);
        return $this;
    }

    /**
     *一般提示.
     *
     * @param string $msg 提示语.
     *
     * @return $this.
     */
    public function addMessageInfo($msg)
    {
        $this->addSMessage($msg, self::TYPE_INFO);
        return $this;
    }

    /**
     *获取消息提示.
     *
     * @param string $type 提示类型.
     *
     * @return array.
     */
    public function getMessage($type = self::TYPE_ALL)
    {
        return isset($this->message[$type]) ? $this->message[$type] : array();
    }
}