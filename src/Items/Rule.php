<?php

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Sexceptions\Sexception;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

class Rule extends AnyItem
{

    private $logicItemParent; // LogicItem
    private $left; // ContentItem
    private $comp; // Comp
    private $right; // ContentItem

    public static function make($left, $comp, $right=null) : Rule
    {

        if (is_null($right) === true) {
            $right = $comp;
            $comp = 'eq';
        }

        if (!is_a($left, 'Przeslijmi\Shortquery\Items\ContentItem')) {
            $left = new Field($left);
        }
        if (!is_a($comp, 'Przeslijmi\Shortquery\Items\Comp')) {

            try {
                $comp = new Comp($comp);
            } catch (Sexception $e) {
                throw (new MethodFopException('creationOfCompFailed', $e))->addInfo('syntax', $comp);
            }
        }
        if (!is_a($right, 'Przeslijmi\Shortquery\Items\ContentItem')) {
            if (is_array($right)) {
                $right = Func::make('in', $right);
            } else {
                $right = new Val($right);
            }
        }

        return new Rule($left, $comp, $right);
    }

    public function __construct(ContentItem $left, Comp $comp, ContentItem $right)
    {

        $this->left = $left;
        $this->comp = $comp;
        $this->right = $right;

        $this->left->setRuleParent($this);
        $this->comp->setRuleParent($this);
        $this->right->setRuleParent($this);
    }

    public function getLeft() : ContentItem
    {

        return $this->left;
    }

    public function getComp() : Comp
    {

        return $this->comp;
    }

    public function getRight() : ContentItem
    {

        return $this->right;
    }

    public function setLogicItemParent(LogicItem $logicItemParent) : void
    {

        $this->logicItemParent = $logicItemParent;
    }

    public function getLogicItemParent() : LogicItem
    {

        return $this->logicItemParent;
    }
}
