//<--------- Start Payment -------//>
(function($) {
	"use strict";

	const divInstall = document.getElementById('installContainer');
	const butInstall = document.getElementById('butInstall');

  if (divInstall && butInstall) {
    window.addEventListener('beforeinstallprompt', (event) => {
      // Prevent the mini-infobar from appearing on mobile.
      event.preventDefault();
      console.log('Ok', 'beforeinstallprompt', event);
      // Stash the event so it can be triggered later.
      window.deferredPrompt = event;
      // Remove the 'display-none' class from the install button container.
      divInstall.classList.toggle('display-none', false);
    });
    
    butInstall.addEventListener('click', async () => {
      console.log('Ok', 'butInstall-clicked');
      const promptEvent = window.deferredPrompt;
      if (!promptEvent) {
        // The deferred prompt isn't available.
        return;
      }
      // Show the install prompt.
      promptEvent.prompt();
      // Log the result
      const result = await promptEvent.userChoice;
      console.log('Ok', 'userChoice', result);
      // Reset the deferred prompt variable, since
      // prompt() can only be called once.
      window.deferredPrompt = null;
      // Hide the install button.
      divInstall.classList.toggle('display-none', true);
    });
    
    window.addEventListener('appinstalled', (event) => {
      console.log('Ok', 'appinstalled', event);
      // Clear the deferredPrompt so it can be garbage collected
      window.deferredPrompt = null;
    });
  }
  
})(jQuery);
