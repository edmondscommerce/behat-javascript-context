<?php

function getContainerIp() {
    $commandToExecute = 'ip addr show eth0 | grep "inet\b" | awk \'{print $2}\' | cut -d/ -f1';
    exec($commandToExecute, $commandOutput, $exitCode);

    return array_pop($commandOutput);
}