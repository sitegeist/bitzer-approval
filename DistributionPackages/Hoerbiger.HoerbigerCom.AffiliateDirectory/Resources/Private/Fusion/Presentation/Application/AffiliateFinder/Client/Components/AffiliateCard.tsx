import * as React from "react";
import {Affiliate} from "../Definitions";

export const AffiliateCard = (affiliate: Affiliate): JSX.Element => (
	<div className="flex flex-col space-y-ch bg-light p-xs shadow-textbox hover:shadow-textbox-hover">
		<b>{affiliate.legalName}</b>
		<div className="flex flex-col">
			<span>{affiliate.streetAddress}</span>
			<span>{affiliate.postalCode} {affiliate.addressLocality}</span>
			<span>{affiliate.addressCountry}</span>
		</div>
		<div className="flex flex-col">
			<a href={`mailto:${affiliate.email}`}>{affiliate.email}</a>
			<a href={`tel:${affiliate.telephone}`}>{affiliate.telephone}</a>
		</div>
		<div className="mt-minus-ch ml-minus-ch flex flex-wrap divide-x">
			{Array.from(new Set(affiliate.tags)).map(tag => (
				<b
					key={tag}
					className="uppercase text-xs px-ch mt-ch"
				>{tag}</b>
			))}
		</div>
	</div>
);
