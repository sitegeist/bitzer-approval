import * as React from "react";
import cx from "classnames";

import {Article, Labels} from "../Definitions";

interface ArticleCardProps {
	article: Article;
	labels: Labels;
}
export const ArticleCard = ({labels, article}: ArticleCardProps): JSX.Element => (
	<div className={cx(
		"[Application.ArticleOverview.ArticleCard]",
		"flex flex-col w-full"
	)}>
		<div className="w-full h-0 pt-ratio-2-1 relative z-30">
			<div className="absolute inset-0">
				<a href={article.url}>
					<img {...article.image} className="w-full h-full object-cover object-center"/>
				</a>
			</div>
		</div>
		<div className="flex-grow flex flex-col p-xs mx-sm mt-minus-sm bg-light z-50 border border-neutral-lighter shadow-textbox">
			<div className="uppercase font-sans-bold text-base flex space-x-ch items-center flex-nowrap max-w-full min-w-0">
				<span className="text-light-contrast flex-shrink-0">{article.datePublished}</span>
				<span className="text-light-contrast flex-shrink-0">ꞏ</span>
				<span className="text-brand whitespace-nowrap overflow-hidden overflow-ellipsis">{article.applicationWorld}</span>
			</div>

			<h2
				className="text-3xl lg:text-4xl text-brand font-sans-bold  mt-ch-xs"
				dangerouslySetInnerHTML={{__html: article.headline}}
			></h2>

			<div
				className="flex-grow prose max-w-none mt-ch"
				dangerouslySetInnerHTML={{__html: article.abstract}}
			></div>

			<a
				className="flex items-center min-h-touch min-w-touch text-brand font-sans-bold text-xl mt-xs"
				href={article.url}>
				{labels.learnMore}
			</a>
		</div>
	</div>
);
