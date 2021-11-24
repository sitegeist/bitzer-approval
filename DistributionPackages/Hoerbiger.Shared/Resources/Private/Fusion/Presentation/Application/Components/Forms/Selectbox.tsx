import * as React from "react";
import cx from "classnames";
import {Field, FieldInputProps, FieldRenderProps} from "react-final-form";

import {Error, Label} from "./";
import {StampedeIcon} from "../";
import {SelectboxOption} from "../../Definitions";
import {Hooks} from "../../Utils";

type SelectboxProps = {
	name: string;
	label: string;
	placeholder?: string;
	options?: SelectboxOption[];
	initialValue?: string;
	validate?: (value: any) => string|undefined;
};
export const Selectbox = (props: SelectboxProps): JSX.Element => {
	const [isOpen, setIsOpen] = React.useState(false);

	const toggle = () => setIsOpen(state => !state);
	const close = () => setIsOpen(false);

	const refNode: React.RefObject<HTMLDivElement> = Hooks.useHandlerOnOuterClick(close);
	const onSelect = (input: FieldInputProps<string, HTMLElement>, key: string) => () => {
		input.onChange(key);
		close();
	};

	const options = [
		props.placeholder && {key: "", value: props.placeholder},
		...(props.options ?? [])
	].filter(Boolean) as SelectboxOption[];

	return (
		<Field
			name={props.name}
			initialValue={props.initialValue}
			validate={props.validate}
		>
			{({input, meta}: FieldRenderProps<string>) => (
				<div
					ref={refNode}
					className={cx(
						"w-full grid grid-rows-form-field relative z-form-field",
						isOpen && "z-form-field-elevated"
					)}
				>
					<Label label={props.label} isForRequired={Boolean(props.validate)}/>
					<div
						onClick={toggle}
						className={cx(
							"max-h-touch min-h-touch border border-brand bg-light rounded-ch-xs px-ch focus:outline-brand",
							"flex items-center justify-between cursor-pointer",
						)}
					>
						<div className="">
							<span className={cx(
								input.value === "" && "text-neutral-darker",
							)}>
								{
									(() => options
										.find(({key}) => key === input.value)
										?.value
									)()
										?? props.placeholder
										?? ""
								}
							</span>
						</div>
						<StampedeIcon
							iconName="chevronDown"
							spin={isOpen}
						/>
					</div>
					<Error {...meta}/>
					{
						isOpen && (
							<div className={cx(
								"bg-light absolute top-full left-0 right-0 border border-brand rounded-b-ch-xs border-t-0 pt-2 -translate-y-2",
								"overflow-y-auto max-h-[250px]"
							)}>
								{
									options.map(({key, value}) => (
										<div
											key={key}
											onClick={onSelect(input, key)}
											className={cx(
												"min-h-touch flex items-center px-ch hover:bg-brand/20 cursor-pointer",
												key === "" && "text-neutral-darker",
												input.value === "" && key === "" && "hidden",
											)}
										>
											{value ?? key}
										</div>
									))
								}
							</div>
						)
					}
				</div>
			)}
		</Field>
	);
};


/*
import styles from './index.css';

import * as React from 'react';
import classNames from 'classnames';

import {Icon} from '@shared/Components';
import {useHandlerOnOuterClick} from '@shared/Utils/Hooks';
import {TSelectboxProps, TOption} from '../definitions';

const ColorIndicator = ({color}: {color: string;}) => color
	? (
		<div
			style={{backgroundColor: color}}
			className={styles.colorIndicator}
		></div>
	)
	: null;

export const Selectbox = ({options, onUpdate, value, label, isInvalid, hasBorder, theme = 'isFilter', className}: TSelectboxProps) => {
	const currentOptionIndex = options.findIndex(option => option.value === value);
	const currentOption = options[currentOptionIndex];

	const [isOpen, updateIsOpen] = React.useState(false);
	const node: React.RefObject<HTMLDivElement> = useHandlerOnOuterClick(() => updateIsOpen(false));
	const input: React.RefObject<HTMLInputElement> = React.useRef(null);

	const toggle = () => updateIsOpen(state => !state);
	const update = (value: string|number) => {
		onUpdate(value);
		toggle();
	};

	const onKeyPress = (e: React.KeyboardEvent<HTMLInputElement>) => {
		switch (e.key) {
			case 'Enter':
			case ' ':
				toggle();
				break;
			case 'Escape':
				updateIsOpen(false);
				break;
			case 'ArrowDown': {
				const nextOption = currentOptionIndex >= 0
					? options[(currentOptionIndex + 1) % options.length]
					: options[0];

				onUpdate(nextOption.value);
				break;
			}
			case 'ArrowUp': {
				const nextOption = currentOptionIndex >= 0
					? options[(options.length + currentOptionIndex - 1) % options.length]
					: options[options.length - 1];

				onUpdate(nextOption.value);
				break;
			}
			default:
				return;
		}

		e.preventDefault();
		e.stopPropagation();
	};

	const onClick = (e: React.MouseEvent<HTMLDivElement>) => {
		input.current?.focus();
	};

	return (
		<div
			ref={node}
			onClick={onClick}
			data-is-invalid={isInvalid ? "1" : "0"}
			className={classNames(
				styles.selectbox,
				isInvalid && styles.isInvalid,
				hasBorder && styles.hasBorder,
				theme === 'isForm' && styles.isForm,
				className
			)}
		>
			<input
				ref={input}
				type="checkbox"
				onKeyDown={onKeyPress}
			/>
			{
				label !== undefined &&
					<label>{label}</label>
			}
			<div
				onClick={toggle}
				className={styles.header}
			>
				<div className={styles.headerLabel}>
					<ColorIndicator color={currentOption?.payload?.color}/>
					<span>{currentOption?.label || currentOption?.value || ''}</span>
				</div>
				<Icon name="ChevronDown" spin={isOpen}/>
			</div>
			{
				isOpen && (
					<div className={styles.options}>
						{
							options.map((option: TOption) => (
								<div
									key={option.value}
									onClick={() => update(option.value)}
									className={classNames(
										styles.option,
										currentOption === option && styles.isHighlighted,
									)}
								>
									{option.label || option.value}
									<ColorIndicator color={option.payload?.color}/>
								</div>
							))
						}
					</div>
				)

			}
		</div>
	);
}
*/
