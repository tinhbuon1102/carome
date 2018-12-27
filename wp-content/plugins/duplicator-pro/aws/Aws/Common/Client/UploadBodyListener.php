<?php
/**
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace DuplicatorPro\Aws\Common\Client;

use DuplicatorPro\Aws\Common\Exception\InvalidArgumentException;
use DuplicatorPro\Guzzle\Common\Event;
use DuplicatorPro\Guzzle\Http\EntityBody;
use DuplicatorPro\Guzzle\Service\Command\AbstractCommand as Command;
use DuplicatorPro\Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Prepares the body parameter of a command such that the parameter is more flexible (e.g. accepts file handles) with
 * the value it accepts but converts it to the correct format for the command. Also looks for a "Filename" parameter.
 */
class UploadBodyListener implements EventSubscriberInterface
{
    /**
     * @var array The names of the commands of which to modify the body parameter
     */
    protected $commands;

    /**
     * @var string The key for the upload body parameter
     */
    protected $bodyParameter;

    /**
     * @var string The key for the source file parameter
     */
    protected $sourceParameter;

    /**
     * @param array  $commands        The commands to modify
     * @param string $bodyParameter   The key for the body parameter
     * @param string $sourceParameter The key for the source file parameter
     */
    public function __construct(array $commands, $bodyParameter = 'Body', $sourceParameter = 'SourceFile')
    {
        $this->commands = $commands;
        $this->bodyParameter = (string) $bodyParameter;
        $this->sourceParameter = (string) $sourceParameter;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array('command.before_prepare' => array('onCommandBeforePrepare'));
    }

    /**
     * Converts filenames and file handles into EntityBody objects before the command is validated
     *
     * @param Event $event Event emitted
     * @throws InvalidArgumentException
     */
    public function onCommandBeforePrepare(Event $event)
    {
        /** @var Command $command */
        $command = $event['command'];
        if (in_array($command->getName(), $this->commands)) {
            // Get the interesting parameters
            $source = $command->get($this->sourceParameter);
            $body = $command->get($this->bodyParameter);

            // If a file path is passed in then get the file handle
            if (is_string($source) && file_exists($source)) {
                $body = fopen($source, 'r');
            }

            // Prepare the body parameter and remove the source file parameter
            if (null !== $body) {
                $command->remove($this->sourceParameter);
                $command->set($this->bodyParameter, EntityBody::factory($body));
            } else {
                throw new InvalidArgumentException("You must specify a non-null value for the ".esc_html($this->bodyParameter)." or ".esc_html($this->sourceParameter)." parameters.");
            }
        }
    }
}
