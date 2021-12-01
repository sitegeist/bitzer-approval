import * as React from "react";
import cx from "classnames";

import {ApplicationData} from "./Definitions";
import {ArticleCard} from "./Components/ArticleCard";

export const Client = ({articles, labels}: ApplicationData): JSX.Element => {
	const ALL_FILTER = React.useRef<string>(labels.all);
	const availableFilters = React.useRef<string[]>([
		ALL_FILTER.current,
		...[...new Set(articles.map(article => article.applicationWorld))]
	]);
	const [currentFilter, setCurrentFilter] = React.useState(ALL_FILTER.current);

	return (
		<div className={cx(
			"[Application.ArticleOverview.Client]",
			"grid grid-cols-1 gap-md"
		)}>
			<div className={"flex flex-wrap justify-center items-center gap-xs mx-auto"}>
				{
					availableFilters.current.map(filter => (
						<div
							key={filter}
							className={cx(
								"px-xs min-h-touch flex items-center",
								"whitespace-nowrap border border-brand uppercase font-sans-bold",
								filter === currentFilter
									? "bg-brand text-brand-contrast"
									: "bg-light text-light-contrast cursor-pointer"
							)}
							onClick={() => setCurrentFilter(filter)}
						>
							{filter}
						</div>
					))
				}
			</div>
			<div className="grid grid-cols-1 md:grid-cols-2 gap-xl md:gap-sm">
				{
					articles
						.filter(article => currentFilter === ALL_FILTER.current || currentFilter === article.applicationWorld)
						.map(article => (
							<ArticleCard
								key={article.url}
								{...{article, labels}}
							/>
						))
				}
			</div>
		</div>
	);
};

export * as Definitions from "./Definitions";

