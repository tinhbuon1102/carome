<?php
// Include locator for awkward elements
use \Codeception\Util\Locator;

class Simple_Frontend_Cest {

	public $outofstock_url = '';
	public $instock_url    = '';

	// Generic
	// Save the single product URL when we first visit the page
	public function NoAddToCartButtonShowsTest( AcceptanceTester $I ) {
		$I->amOnPage( '/shop' );
		$I->click( 'li.outofstock.product-type-simple a.button.product_type_simple' );
		$this->outofstock_url = $I->grabFullUrl();
		$I->dontSeeElement( 'a.single_add_to_cart_button' );
	}

	// Logged In & Out of Stock
	public function LoggedInAddToWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( $this->outofstock_url );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have been added to the waitlist for this product' );
	}

	public function ProductShowsOnUsersWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( '/my-account' );
		$I->waitForElement( 'a[href="' . $this->outofstock_url . '"]', 5 );
		$I->seeElement( 'a[href="' . $this->outofstock_url . '"]' );
	}

	public function LoggedInRemoveFromWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( $this->outofstock_url );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have been removed from the waitlist for this product' );
	}

	public function ProductDoesntShowOnUsersWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( '/my-account' );
		$I->dontSeeElement('a[href="' . $this->outofstock_url . '"]' );
	}

	// Logged in and In Stock
	public function LoggedInUserCanAddToCartTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( '/shop' );
		$I->click( 'li.instock.product-type-simple a.woocommerce-LoopProduct-link' );
		$this->instock_url = $I->grabFullUrl();
		$I->click( 'button.single_add_to_cart_button' );
		$I->see( 'has been added to your cart.' );
		$I->dontSee( 'You have been added to the waitlist for this product' );
		$I->dontSee( 'You have been removed from the waitlist for this product' );
	}

	// Logged Out
	public function LoggedOutNewUserAddToWaitlistTest( AcceptanceTester $I ) {
		$I->amOnPage( $this->outofstock_url );
		$I->fillField( 'input#wcwl_email', 'new@testuser.com' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have been added to the waitlist for this product' );
	}

	public function LoggedOutExistingUserAddToWaitlistTest( AcceptanceTester $I ) {
		$I->amOnPage( $this->outofstock_url );
		$I->fillField( 'input#wcwl_email', 'joey@pie.co.de' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have been added to the waitlist for this product' );
	}

	public function LoggedOutInvalidEmailTest( AcceptanceTester $I ) {
		$I->amOnPage( $this->outofstock_url );
		$I->fillField( 'input#wcwl_email', 'newtestuser.com' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You must provide a valid email address to join the waitlist for this product' );
	}
}
