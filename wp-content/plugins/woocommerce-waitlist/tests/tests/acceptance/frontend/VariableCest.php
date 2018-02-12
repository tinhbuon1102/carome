<?php
// Include locator for awkward elements
use \Codeception\Util\Locator;

class Variable_Frontend_Cest {


	public $outofstock_url = '';
	public $instock_url    = '';

	// Generic
	// Save the outofstock variable URL for later use
	public function NoAddToCartButtonShowsTest( AcceptanceTester $I ) {
		$I->amOnPage( '/shop' );
		$I->selectOption( 'select.orderby', 'Sort by average rating' );
		$I->click( 'li.outofstock.product-type-variable a.button.product_type_variable' );
		$this->outofstock_url = $I->grabFullUrl();
		$I->dontSeeElement( 'a.woocommerce_waitlist' );
		$I->selectOption( 'select#pa_color', 'Blue' );
		$I->dontSeeElement( 'button.single_add_to_cart_button' );
	}

	// Logged In
	public function LoggedInAddToWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( $this->outofstock_url );
		$I->selectOption( 'select#pa_color', 'Blue' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have been added to the waitlist for this product' );
	}

	public function ProductShowsOnUsersWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( '/my-account' );
		$I->seeElement( 'a[href="' . $this->outofstock_url . '?attribute_pa_color=blue"]' );
	}

	public function LoggedInRemoveFromWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( $this->outofstock_url );
		$I->selectOption( 'select#pa_color', 'Blue' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have been removed from the waitlist for this product' );
	}

	public function ProductDoesntShowOnUsersWaitlistTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( '/my-account' );
		$I->dontSeeElement( 'a[href="' . $this->outofstock_url . '?attribute_pa_color=blue"]' );
	}

	public function LoggedInUserCanAddToCartTest( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage( '/shop' );
		$I->selectOption( 'select.orderby', 'Sort by price: high to low' );
		$I->click( 'li.instock.product-type-variable a.button.product_type_variable' );
		$this->instock_url = $I->grabFullUrl();
		$I->selectOption( 'select#pa_color', 'Blue' );
		$I->click( 'button.single_add_to_cart_button' );
		$I->see( 'has been added to your cart.' );
		$I->dontSee( 'You have been added to the waitlist for this product' );
		$I->dontSee( 'You have been removed from the waitlist for this product' );
	}

	// Logged Out
	public function LoggedOutNewUserAddToWaitlistTest( AcceptanceTester $I ) {
		$I->amOnPage( $this->outofstock_url );
		$I->selectOption( 'select#pa_color', 'Blue' );
		$I->fillField( 'input#wcwl_email', 'new@testuser.com' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have been added to the waitlist for this product' );
	}

	public function LoggedOutExistingUserAddToWaitlistTest( AcceptanceTester $I ) {
		$I->amOnPage( $this->outofstock_url );
		$I->selectOption( 'select#pa_color', 'Blue' );
		$I->fillField( 'input#wcwl_email', 'joey@pie.co.de' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You have been added to the waitlist for this product' );
	}

	public function LoggedOutInvalidEmailTest( AcceptanceTester $I ) {
		$I->amOnPage( $this->outofstock_url );
		$I->selectOption( 'select#pa_color', 'Blue' );
		$I->fillField( 'input#wcwl_email', 'newtestuser.com' );
		$I->click( 'a.woocommerce_waitlist' );
		$I->see( 'You must provide a valid email address to join the waitlist for this product' );
	}
}
