<?php
/*
  $Id: salemaker_info.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'SaleMaker');
define('SUBHEADING_TITLE', 'SaleMaker Usage Tips:');
define('INFO_TEXT', '<ul>
                      <li>
                        Always use a \'.\' as decimal point in deduction and pricerange.
                      </li>
                      <li>
                        Enter amounts in the same currency as you would when creating/editing a product.
                      </li>
                      <li>
                        In the Deduction fields, you can enter an amount or a percentage to deduct,
                        or a replacement price. (eg. deduct $5.00 from all prices or deduct 10% from
                        all prices or change all prices to $25.00)
                      </li>
                      <li>
                        Entering a pricerange narrowes down the productrange that will be affected. (eg.
                        products from $50.00 to $150.00)
                      </li>
                      <li>
                        You must choose the action to take if a product is a special <i>and</i> is subject to this sale:
						<ul>
                          <li>
                            Ignore Specials Price<br>
                            The salededuction will be applied to the regular price of the product.
                            (eg. Regular price $10.00, Specials price is $9.50. SaleCondition is 10%,
                            The product\'s final price will show $9.00 on sale. The Specials price is ignored.)
                          </li>
                          <li>
                            Ignore SaleCondition<br>
                            The salededuction will not be applied to Specials. The Specials price will show just like
                            when there is no sale defined. (eg. Regular price $10.00, Specials price is $9.50.
                            SaleCondition is 10%. The product\'s final price will show $9.50 on sale.
                            The SalesCondition is ignored.)
                          </li>
                          <li>
                            Apply SaleCondition to Specials Price<br>
                            The salededuction will be applied to the Specials price. A compounded price will show.
                            (eg. Regular price $10.00, Specials price is $9.50, SaleCondition is 10%. The product\'s
                            final price will show $8.55. And additional 10% off the Specials price.)
                          </li>
                        </ul>
                      </li>
                      <li>
                        Leaving the start date empty will start the sale immediately.
                      </li>
                      <li>
                        Leave the end date empty if you do not want the sale to expire.
                      </li>
                      <li>
                        Checking a category automatically includes the sub-categories.
                      </li>
                    </ul>');
define('TEXT_CLOSE_WINDOW', '[ close window ]');
?>
