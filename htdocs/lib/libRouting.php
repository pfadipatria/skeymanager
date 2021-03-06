<?php

/**
 * handle routing
 *
 * route request and check for logged in users
 *
 * @param boolean $loggedIn is the user logged in
 *
 * @return void
 */
function routing($loggedIn = false){
    $menuPath = 'login';

    header('Content-Type: text/html; charset=utf8');

    if (!$loggedIn) {
      showLoginPage();
      return;
    }

   $menuPath = getMenuPath();

   if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['q'])) {
      showSearchPage($menuPath);
      return;
   }

   switch($menuPath) {
      case 'keys':
         showKeysPage();
         break;
      case 'key':
         showKeysPage();
         break;
      case 'locks':
         showLocksPage();
         break;
      case 'lock':
         showLocksPage();
         break;
      case 'person':
         showPersonPage();
         break;
      case 'history':
         showHistoryPage();
         break;
      case 'login':
         showLoginPage();
         break;
      case 'help':
         showHelpPage();
         break;
      case 'search':
         showSearchPage();
         break;
      case 'pref':
         showPrefPage();
         break;
      default:
         showStartPage();
   }
}

function showKeysPage(){
   switch(getMenuPath('2')){
      case 'list':
         showKeyListPage();
         break;
      case 'permissions':
         showPermissionMatrixPage();
         break;
      case '':
         showKeyListPage();
         break;
      case 'show':
         showKeyDetailsPage(getMenuPath('3'));
         break;
      case 'search':
         showSearchPage('key');
         break;
      case 'add':
         showKeyEditPage();
         break;
      case 'edit':
         showKeyEditPage(getMenuPath('3'));
         break;
      case (preg_match('/^[0-9]+$/', getMenuPath('2')) ? true : false) :
         if(getMenuPath('3') == 'edit') {
            showKeyEditPage(getMenuPath('2'));
         } else if (getMenuPath('3') == 'allow') {
            showKeyAllowPage(getMenuPath('2'));
         } else if (getMenuPath('3') == 'addhistory') {
            showHistoryCommentPage(getMenuPath('2'));
         } else if (getMenuPath('3') == 'delete') {
            showKeyDeletePage(getMenuPath('2'));
         } else if (getMenuPath('3') == 'holder' && getMenuPath('4') == 'delete') {
            showKeyDeleteHolderPage(getMenuPath('2'));
         } else {
            showKeyDetailsPage(getMenuPath('2'));
         }
         break;
      default:
         showKeyListPage();
   }
}

function showLocksPage(){
   switch(getMenuPath('2')){
      case 'list':
         showLockListPage();
         break;
      case '':
         showLockListPage();
         break;
      case 'show':
         showLockDetailsPage(getMenuPath('3'));
         break;
      case 'search':
         showSearchPage('lock');
         break;
      case 'add':
         showLockEditPage();
         break;
      case 'edit':
         showLockEditPage(getMenuPath('3'));
         break;
      case (preg_match('/^[0-9]+$/', getMenuPath('2')) ? true : false) :
         if(getMenuPath('3') == 'edit') {
            showLockEditPage(getMenuPath('2'));
         } else if (getMenuPath('3') == 'deny') {
            showLockDenyPage(getMenuPath('2'));
         } else if (getMenuPath('3') == 'addhistory') {
            showHistoryCommentPage(null ,getMenuPath('2'));
         } else if (getMenuPath('3') == 'delete') {
            showLockDeletePage(getMenuPath('2'));
         } else {
            showLockDetailsPage(getMenuPath('2'));
         }
         break;
      default:
         showLockListPage();
   }
}

function showPersonPage(){
   switch(getMenuPath('2')){
      case 'list':
         showPersonListPage();
         break;
      case '':
         showPersonListPage();
         break;
      case 'show':
         showPersonDetailsPage(getMenuPath('3'));
         break;
      case 'edit':
         showPersonEditPage(getMenuPath('3'));
         break;
      case 'add':
         showPersonEditPage();
         break;
      case 'search':
         showSearchPage('person');
         break;
      case 'history':
         showPersonHistoryPage();
         break;
      case (preg_match('/^[0-9]+$/', getMenuPath('2')) ? true : false) :
         if(getMenuPath('3') == 'edit') {
            showPersonEditPage(getMenuPath('2'));
         } else if (getMenuPath('3') == 'addhistory') {
            showHistoryCommentPage(null, null, getMenuPath('2'));
         } else if (getMenuPath('3') == 'delete') {
            showPersonDeletePage(getMenuPath('2'));
         } else {
            showPersonDetailsPage(getMenuPath('2'));
         }
         break;
      default:
         showPersonListPage();
   }
}

function showHelpPage(){
   showHelpDefaultPage();
}

function showHistoryPage(){
   showHistoryListPage();
}

function showPrefPage(){
   showPrefListPage();
}

