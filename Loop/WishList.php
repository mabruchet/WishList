<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace WishList\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Type\IntListType;
use Thelia\Type\TypeCollection;
use WishList\Model\Base\WishListQuery;

/**
 *
 * WishList loop
 *
 *
 * Class WishList
 * @package WishList\Loop
 * @author Michaël Espeche <mespeche@openstudio.fr>
 */
class WishList extends BaseLoop implements PropelSearchLoopInterface
{
    protected $timestampable = true;

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {

        $productIds = array();

        foreach ($loopResult->getResultDataCollection() as $wishlist) {
            $productIds[] = $wishlist->getProductId();
        }

        if (!empty($productIds)) {
            $productIdsList = implode(',', $productIds);

            $loopResultRow = new LoopResultRow($wishlist);

            $loopResultRow
                ->set("WISHLIST_PRODUCT_LIST", $productIdsList)
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }

    /**
     *
     * define all args used in your loop
     *
     *
     * example :
     *
     * public function getArgDefinitions()
     * {
     *  return new ArgumentCollection(
     *       Argument::createIntListTypeArgument('id'),
     *           new Argument(
     *           'ref',
     *           new TypeCollection(
     *               new Type\AlphaNumStringListType()
     *           )
     *       ),
     *       Argument::createIntListTypeArgument('category'),
     *       Argument::createBooleanTypeArgument('new'),
     *       Argument::createBooleanTypeArgument('promo'),
     *       Argument::createFloatTypeArgument('min_price'),
     *       Argument::createFloatTypeArgument('max_price'),
     *       Argument::createIntTypeArgument('min_stock'),
     *       Argument::createFloatTypeArgument('min_weight'),
     *       Argument::createFloatTypeArgument('max_weight'),
     *       Argument::createBooleanTypeArgument('current'),
     *
     *   );
     * }
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            new Argument(
                'customer',
                new TypeCollection(
                    new IntListType()
                )
            )
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $search = WishListQuery::create();

        $search->filterByCustomerId($this->getCustomer(), Criteria::IN);

        return $search;
    }

}