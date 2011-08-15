<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\SerializerBundle\Serializer\Handler;

use Symfony\Component\Form\FormError;
use Symfony\Component\Translation\TranslatorInterface;
use JMS\SerializerBundle\Serializer\Handler\SerializationHandlerInterface;
use JMS\SerializerBundle\Serializer\VisitorInterface;
use JMS\SerializerBundle\Serializer\XmlSerializationVisitor;

class FormErrorHandler implements SerializationHandlerInterface
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function serialize(VisitorInterface $visitor, $data, $type, &$handled)
    {
        if (!$data instanceof FormError) {
            return;
        }

        $handled = true;
        $message = $this->translator->trans($data->getMessageTemplate(), $data->getMessageParameters(), 'validators');

        if ($visitor instanceof XmlSerializationVisitor) {
            if (null === $visitor->document) {
                $visitor->document = $visitor->createDocument(null, null, true);
            }

            return $visitor->document->createCDATASection($message);
        }

        return $message;
    }
}