/* global themeData */

function replaceIcon(variations, serviceName, icon) {
    const i = variations.findIndex(({ attributes }) => attributes.service === serviceName);
    const newVar = variations[i];
    newVar.icon = icon;
    return { i, answer: newVar };
}

function filterSocialLinkIcons(settings, name) {
    if ([
        'core/social-link',
        'outermost/social-sharing-link',
    ].includes(name)
    ) {
        const { variations } = settings;
        const services = themeData?.settings?.services;
        for (const service in services) {
            const icon = services?.[service]?.icon;
            if (!icon) {
                continue;
            }
            const IconComponent = () => <div dangerouslySetInnerHTML={{ __html: icon }} />;
            const { i, answer } = replaceIcon(variations, service, IconComponent);
            variations[i] = answer;
        }
        return { ...settings, variations };
    }
    return settings;
}

export default function replaceIcons() {
    wp.hooks.addFilter(
        'blocks.registerBlockType',
        'replace-social-services-icons/replace-social-services-icons',
        filterSocialLinkIcons,
    );
}
