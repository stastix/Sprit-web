<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class BadWordsListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onFormSubmit',
        ];
    }

    public function onFormSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $comment = $data->getContext();
        $comment = $this->replaceBadWords($comment);

        $data->setContext($comment);
    }

    private function replaceBadWords($text)
    {
        $input = "bad1,bad2,bad3";
        $badWords = explode(",", $input);

        $text = str_ireplace($badWords, '****', $text);

        return $text;
    }
}
