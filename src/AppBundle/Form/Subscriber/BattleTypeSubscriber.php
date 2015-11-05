<?php

namespace AppBundle\Form\Subscriber;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BattleTypeSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SUBMIT => 'preSubmit');
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $battle = $event->getData();

        if (isset($battle['computerWeapon']) && isset($battle['victor'])) {
            $battle['computerWeapon'] = rand(0, 4);
            $battle['victor'] = $this->setVictor($battle);
        }

        $event->setData($battle);
    }

    /**
     * Sets the victor of the battle
     * 
     * @param $battle
     * @return int
     */
    private function setVictor($battle)
    {
        $uWeapon = $battle['userWeapon'];
        $comWeapon = $battle['computerWeapon'];
        return $this->determineVictor($uWeapon, $comWeapon);
    }

    /**
     * Determines the victor of the battle
     * Possible values are: 0 = player wins, 1 = computer wins, 2 = Tie
     *
     * @param int $u
     * @param int $c
     * @return int $result
     */
    private function determineVictor($u, $c)
    {
        $victor = (5 + ((int) $u - (int) $c)) % 5;

        if ($victor == 1 || $victor == 3) {
            return 0;
        } elseif ($victor == 2 || $victor == 4) {
            return 1;
        }

        return 2;
    }
}
