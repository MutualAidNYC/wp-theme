import classnames from 'classnames';

import { getColorClassName, RichText } from '@wordpress/block-editor';

import { imageFillStyles } from './edit';

export default function MediaBorderSave( { attributes } ) {
	const {
		className,
		mediaAlt,
		mediaId,
		mediaUrl,
		focalPoint,
		content,
		color,
		customColor,
	} = attributes;

	const colorClass = getColorClassName( 'color', color );

	const mediaClasses = classnames( className, {
		'has-text-color has-background': color || customColor,
		[ colorClass ]: colorClass,
	} );

	return (
		<div className={ mediaClasses }>
			<figure
				className="wp-block-mutualaidnyc-media-border__media"
				style={ imageFillStyles( mediaUrl, focalPoint ) }
			>
				<img
					src={ mediaUrl }
					alt={ mediaAlt }
					className={ `wp-image-${ mediaId }` }
				/>
			</figure>
			<div className="wp-block-mutualaidnyc-media-border__content">
				<RichText.Content value={ content } tagName="p" />
			</div>
		</div>
	);
}
