talentlink_talentportal.TalentPortalEventBus.subscribe("jobs-list", "rendered", function () {
    var translations = talentlink_talentportal.ComponentTranslationFacade;
    var gaCustomLink = translations.get('GA_custom_link', '');
    var url = 'https://emea3.recruitmentplatform.com/apply-app/pages/application-form';

    if(gaCustomLink !== '') {
        $('#lumesse-general-application-wrapper .btn').attr("href", url + gaCustomLink);
    }
});
talentlink_talentportal.TalentPortalEventBus.subscribe("job-detail", "rendered", function (resp) {
    var obs=resp.data.SDPTNAMELEVEL1;

    //hide apply btn for Russia OBS
    if (obs.toLowerCase()==='russia'){
        let applyButtons = document.querySelectorAll('[data-lumesse-jd-action="apply"]');
        for (var i = 0, len = applyButtons.length; i < len; i++) {
            applyButtons[i].style.display = 'none';
        }
    }
});
