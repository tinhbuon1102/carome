<?php
// Include locator for awkward elements
use \Codeception\Util\Locator;

class Grouped_Frontend_Cest {

	public $parent_url    = '';
	public $child_oos_url = '';

	// Logged In
	public function LoggedInAddToWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( '/shop' );
		$I->click( 'li.product-type-grouped a.product_type_grouped' );
		$this->parent_url = $I->grabFullUrl();
		$I->click( 'td.woocommerce-grouped-product-list-item__label a' );
		$this->child_oos_url = $I->grabFullUrl();
		$I->moveBack();
		$I->checkOption( '.wcwl_checkbox' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have updated the selected waitlist/s' );
	}

	public function ProductShowsOnUsersWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( '/my-account' );
		$I->seeElement( 'a[href="' . $this->child_oos_url . '"]' );
	}

	public function LoggedInRemoveFromWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( $this->parent_url );
		$I->uncheckOption( '.wcwl_checkbox' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have updated the selected waitlist/s' );
	}

	public function ProductDoesntShowOnUsersWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( '/my-account' );
		$I->dontSeeElement( 'a[href="' . $this->child_oos_url . '"]' );
	}

	public function LoggedInUserCanAddToCartTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( $this->parent_url );
		$I->click( 'button.single_add_to_cart_button' );
		$I->see( 'Please choose the quantity of items you wish to add to your cart…' );
		$I->dontSee( 'You have updated the selected waitlist/s' );
		$I->fillField( 'quantity[159]', '1' );
		$I->click( 'button.single_add_to_cart_button' );
		$I->see( 'has been added to your cart.' );
		$I->dontSee( 'You have updated the selected waitlist/s' );
	}

	// Logged Out
	public function LoggedOutNewUserAddToWaitlistTest( AcceptanceTester $I ) {
		$I->amOnPage( $this->parent_url );
		$I->checkOption( '.wcwl_checkbox' );
		$I->fillField( 'input#wcwl_email', 'new@testuser.com' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have updated the selected waitlist/s' );
	}

	public function LoggedOutExistingUserAddToWaitlistTest( AcceptanceTester $I ) {
		$I->amOnPage( $this->parent_url );
		$I->checkOption( '.wcwl_checkbox' );
		$I->fillField( 'input#wcwl_email', 'joey@pie.co.de' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have updated the selected waitlist/s' );
	}

	public function LoggedOutInvalidEmailTest( AcceptanceTester $I ) {
		$I->amOnPage( $this->parent_url );
		$I->checkOption( '.wcwl_checkbox' );
		$I->fillField( 'input#wcwl_email', 'newtestuser.com' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You must provide a valid email address to join the waitlist for this product' );
	}
}
