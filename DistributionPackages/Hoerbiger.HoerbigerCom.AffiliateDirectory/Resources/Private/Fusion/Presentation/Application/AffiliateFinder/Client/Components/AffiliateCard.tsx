import * as React from "react";
import cx from "classnames";

import {Affiliate} from "../Definitions";
import {StampedeIcon} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components";

type AffiliateCardProps = {
	affiliates: Affiliate[];
}
export const AffiliateCard = ({affiliates}: AffiliateCardProps): JSX.Element => (
	<div className={cx(
		"flex flex-col space-y-xs bg-light p-xs text-base leading-6 font-sans-regular",
		"shadow-textbox hover:shadow-textbox-hover rounded-[5px]"
	)}>
		{
			affiliates.length > 1
				? <AffiliateAddress {...affiliates[0]}/>
				: null
		}
		{
			affiliates.map(affiliate => (
				<div
					key={affiliate.identifier}
					className="flex flex-col space-y-ch-xs"
				>
					<b>{affiliate.legalName}</b>
					{
						affiliates.length > 1
							? null
							: <AffiliateAddress {...affiliate}/>
					}
					<div className="flex flex-col">
						{
							affiliate.email
								? <a href={`mailto:${affiliate.email}`} className="flex items-center gap-ch">
									<StampedeIcon
										iconName="email"
									/>
									{affiliate.email}
								</a>
								: null
						}
						{
							affiliate.telephone
								? <a href={`tel:${affiliate.telephone}`} className="flex items-center gap-ch">
									<StampedeIcon
										iconName="phone"
									/>
									{affiliate.telephone}1111
								</a>
								: null
						}
					</div>
				</div>
			))
		}
	</div>
);

const AffiliateAddress = (affiliate: Affiliate) => (
	<div className="flex flex-col">
		<span>{affiliate.streetAddress}</span>
		<span>{affiliate.postalCode} {affiliate.addressLocality}</span>
		<span>{affiliate.addressCountry}</span>
	</div>
);
