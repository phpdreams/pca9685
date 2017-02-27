<?php namespace PHPDreams\PCA9685;

class PCA9685
{

    public $i2c_set = '/usr/sbin/i2cset -y';

    public $i2c_address;

    public $i2c_bus;

    public $channels = [];

    public function __construct($i2c_bus = 1, $i2c_address = 0x40)
    {
        $this->i2c_bus = $i2c_bus;
        $this->i2c_address = $i2c_address;

        $this->channels = $this->populateChannels();
    }

    public function setPWMpercent($channel, $percent)
    {
        $value = round(4096 * $percent / 100);

        $this->setPWM($channel, $value);
    }

    public function setPWM($channel, $countOff, $countOn = 0)
    {
        list($onHi, $onLo) = $this->get12bit($countOn);
        list($offHi, $offLo) = $this->get12bit($countOff);

        exec("{$this->i2c_set} {$this->i2c_bus} {$this->i2c_address} {$this->channels[$channel][0]} {$onLo}");
        exec("{$this->i2c_set} {$this->i2c_bus} {$this->i2c_address} {$this->channels[$channel][1]} {$onHi}");
        exec("{$this->i2c_set} {$this->i2c_bus} {$this->i2c_address} {$this->channels[$channel][2]} {$offLo}");
        exec("{$this->i2c_set} {$this->i2c_bus} {$this->i2c_address} {$this->channels[$channel][3]} {$offHi}");

    }

    public function setAll($countOff, $countOn = 0)
    {
        list($onHi, $onLo) = $this->get12bit($countOn);
        list($offHi, $offLo) = $this->get12bit($countOff);

        exec("{$this->i2c_set} {$this->i2c_bus} {$this->i2c_address} 250 {$onLo}");
        exec("{$this->i2c_set} {$this->i2c_bus} {$this->i2c_address} 251 {$onHi}");
        exec("{$this->i2c_set} {$this->i2c_bus} {$this->i2c_address} 252 {$offLo}");
        exec("{$this->i2c_set} {$this->i2c_bus} {$this->i2c_address} 253 {$offHi}");

    }

    public function setFrequency($frequency)
    {
        $value = round(25000000 / (4096 * $frequency)) - 1;

        // the PCA9685 has frequency limits, we'll be sure we're within those
        if($value < 3) $value = 3;
        if($value > 255) $value = 255;

        exec("{$this->i2c_set} {$this->i2c_bus} {$this->i2c_address} 254 {$value}");
    }

    private function get12bit($value)
    {
        if($value < 0) $value = 0;
        if($value > 4095) $value = 4095;

        $hi = floor($value / 256);
        $lo = $value % 256;

        return [$hi,$lo];
    }

    /*
     * The 16 PWM channels on the PCA9685 each have four registers from address 6 to 69.
     * This generates an array of the register IDs for each channel
     * as such: $channel[$channelID][onLow,onHi,offLow,offHi]
     */
    private function populateChannels()
    {
        $ch = 0;
        $channels = [];
        for($i=1; $i<=64; $i++) {
            $channels[$ch][] = $i+5;
            if($i%4 == 0) $ch++;
        }
        return $channels;
    }

}
