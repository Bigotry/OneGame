<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Collin <1169986@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace ob;

class IdWork
{
    //开始时间,固定一个小于当前时间的毫秒数即可
    const twepoch =  1474992000000;//2016/9/28 0:0:0
    //机器标识占的位数
    const workerIdBits = 5;
    //数据中心标识占的位数
    const datacenterIdBits = 5;
    //毫秒内自增数点的位数
    const sequenceBits = 12;
    protected $workId = 0;
    protected $datacenterId = 0;
    static $lastTimestamp = -1;
    static $sequence = 0;
    function __construct($workId=0, $datacenterId=0){
        //机器ID范围判断
        $maxWorkerId = -1 ^ (-1 << self::workerIdBits);
        
        if($workId > $maxWorkerId || $workId< 0){
            throw new \Exception("workerId can't be greater than ".$this->maxWorkerId." or less than 0");
        }
        //数据中心ID范围判断
        $maxDatacenterId = -1 ^ (-1 << self::datacenterIdBits);
        if ($datacenterId > $maxDatacenterId || $datacenterId < 0) {
            throw new \Exception("datacenter Id can't be greater than ".$maxDatacenterId." or less than 0");
        }
        //赋值
        $this->workId = $workId;
        $this->datacenterId = $datacenterId;
    }
    //生成一个ID
    public function nextId(){
        $timestamp = $this->timeGen();
        $lastTimestamp = self::$lastTimestamp;
        //判断时钟是否正常
        if ($timestamp < $lastTimestamp) {
            throw new \Exception("Clock moved backwards.  Refusing to generate id for %d milliseconds", ($lastTimestamp - $timestamp));
        }
        //生成唯一序列
        if ($lastTimestamp == $timestamp) {
            $sequenceMask = -1 ^ (-1 << self::sequenceBits);
            self::$sequence = (self::$sequence + 1) & $sequenceMask;
            if (self::$sequence == 0) {
                $timestamp = $this->tilNextMillis($lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }
        
        self::$lastTimestamp = $timestamp;
        
        //时间毫秒/数据中心ID/机器ID,要左移的位数
        $timestampLeftShift = self::sequenceBits + self::workerIdBits + self::datacenterIdBits;
        $datacenterIdShift = self::sequenceBits + self::workerIdBits;
        $workerIdShift = self::sequenceBits;
        //组合4段数据返回: 时间戳.数据标识.工作机器.序列
        $nextId = (($timestamp - self::twepoch) << $timestampLeftShift) |
            ($this->datacenterId << $datacenterIdShift) |
            ($this->workId << $workerIdShift) | self::$sequence;
        
        $id = str_pad(abs($nextId),10,0,STR_PAD_LEFT);
        
        return time() . $id;
    }
    //取当前时间毫秒
    protected function timeGen(){
        $timestramp = (float)sprintf("%.0f", microtime(true) * 1000);
        return  $timestramp;
    }
    //取下一毫秒
    protected function tilNextMillis($lastTimestamp) {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }
}