import * as React from "react";
import cx from "classnames";

import {Affiliate} from "../Definitions";

type AffiliateCardProps = {
	affiliates: Affiliate[];
}
export const AffiliateCard = ({affiliates}: AffiliateCardProps): JSX.Element => (
	<div className={cx(
		"flex flex-col space-y-xs bg-light p-xs text-base leading-6 font-sans-regular",
		"shadow-textbox hover:shadow-textbox-hover"
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
						<a href={`mailto:${affiliate.email}`}>{affiliate.email}</a>
						<a href={`tel:${affiliate.telephone}`}>{affiliate.telephone}</a>
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
