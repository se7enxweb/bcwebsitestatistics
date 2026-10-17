/**
 * BC Website Statistics: the Google tag (gtag.js) configuration, printed by the template operator bc_ga_urchin().
 *
 * The account is the script element's data-bc-ga-id (bcwebsitestatistics.ini [BCWebsiteStatisticsSettings] Urchin),
 * the host name its data-bc-ga-udn (HostName, when not disabled). It does what the inline script before did:
 * window.dataLayer, the global gtag(), gtag('js') and gtag('config'). As a file it runs under a Content Security
 * Policy without 'unsafe-inline'.
 */
(function () {
    var script = document.currentScript;
    var id = script ? script.getAttribute( 'data-bc-ga-id' ) : '';
    if ( !id ) { return; }
    window.dataLayer = window.dataLayer || [];
    window.gtag = function gtag() { window.dataLayer.push( arguments ); };
    window.gtag( 'js', new Date() );
    window.gtag( 'config', id, { cookie_flags: 'SameSite=None;Secure' } );
    var udn = script.getAttribute( 'data-bc-ga-udn' );
    if ( udn ) { window._udn = udn; }
})();
