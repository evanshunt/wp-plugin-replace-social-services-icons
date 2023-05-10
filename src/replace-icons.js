/* global themeData */

function getVariationIndexByServiceName(variations, serviceName) {
    return variations.findIndex(({ attributes }) => attributes.service === serviceName);
}

function replaceIcon(variation, icon) {
    const IconComponent = () => <div dangerouslySetInnerHTML={{ __html: icon }} />;
    variation.icon = IconComponent;
    return variation;
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

            const i = getVariationIndexByServiceName(variations, service);
            if (i === -1) {
                continue;
            }

            variations[i] = replaceIcon(variations[i], icon);
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
