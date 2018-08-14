<?php

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\TermReferenceTree\TermReferenceTree;

class EditPageGeneralVocabularyTermReferenceTree extends TermReferenceTree
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[@id="edit-field-paddle-general-tags"]';
}
