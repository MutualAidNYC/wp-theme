/**
 * External dependencies
 */
import { get } from 'lodash';
import classnames from 'classnames';

import { __ } from '@wordpress/i18n';
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { Component } from '@wordpress/element';
import {
	withNotices,
	PanelBody,
	FocalPointPicker,
	TextareaControl,
	ExternalLink,
} from '@wordpress/components';
import {
	BlockControls,
	MediaPlaceholder,
	MediaReplaceFlow,
	InspectorControls,
	RichText,
	withColors,
	PanelColorSettings,
} from '@wordpress/block-editor';

/**
 * Constants
 */
const ALLOWED_MEDIA_TYPES = [ 'image' ];

export const imageFillStyles = ( url, focalPoint ) => ( {
	backgroundImage: `url(${ url })`,
	backgroundPosition: focalPoint
		? `${ focalPoint.x * 100 }% ${ focalPoint.y * 100 }%`
		: `50% 50%`,
} );

class MediaBorderEdit extends Component {
	constructor() {
		super( ...arguments );
		this.onUploadError = this.onUploadError.bind( this );
		this.onSelectMedia = this.onSelectMedia.bind( this );
	}

	onUploadError( message ) {
		const { noticeOperations } = this.props;
		noticeOperations.removeAllNotices();
		noticeOperations.createErrorNotice( message );
	}

	onSelectMedia( image ) {
		const { setAttributes } = this.props;

		// Try the "large" size URL, falling back to the "full" size URL below.
		const src =
			get( image, [ 'sizes', 'large', 'url' ] ) ||
			get( image, [ 'media_details', 'sizes', 'large', 'source_url' ] );

		setAttributes( {
			mediaAlt: image.alt,
			mediaId: image.id,
			mediaUrl: src || image.url,
			focalPoint: undefined,
		} );
	}

	renderMedia() {
		const {
			noticeUI,
			attributes: { mediaAlt, mediaUrl, mediaId, focalPoint },
		} = this.props;

		if ( ! mediaUrl ) {
			return (
				<MediaPlaceholder
					labels={ {
						title: __( 'Image area' ),
					} }
					onSelect={ this.onSelectMedia }
					accept="image"
					allowedTypes={ [ 'image' ] }
					notices={ noticeUI }
					onError={ this.onUploadError }
				/>
			);
		}

		return (
			<>
				<BlockControls>
					<MediaReplaceFlow
						mediaId={ mediaId }
						mediaURL={ mediaUrl }
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						accept="image/*"
						onSelect={ this.onSelectMedia }
					/>
				</BlockControls>
				<figure
					style={ imageFillStyles( mediaUrl, focalPoint ) }
					className="wp-block-mutualaidnyc-media-border__media"
				>
					<img
						src={ mediaUrl }
						alt={ mediaAlt }
						className={ `wp-image-${ mediaId }` }
					/>
				</figure>
			</>
		);
	}

	render() {
		const {
			className,
			attributes: { mediaUrl, focalPoint, mediaAlt, content },
			setAttributes,
			color,
			setColor,
		} = this.props;

		return (
			<>
				<InspectorControls>
					<PanelColorSettings
						title={ __( 'Colors', 'mutualaidnyc' ) }
						colorSettings={ [
							{
								value: color,
								onChange: setColor,
								label: __( 'Border Color', 'mutualaidnyc' ),
							},
						] }
					/>
					<PanelBody title={ __( 'Media Settings', 'mutualaidnyc' ) }>
						<FocalPointPicker
							label={ __( 'Focal point picker', 'mutualaidnyc' ) }
							url={ mediaUrl }
							value={ focalPoint }
							onChange={ ( value ) =>
								setAttributes( { focalPoint: value } )
							}
						/>
						<TextareaControl
							label={ __( 'Alt text (alternative text)' ) }
							value={ mediaAlt }
							onChange={ ( value ) =>
								setAttributes( { mediaAlt: value } )
							}
							help={
								<>
									<ExternalLink href="https://www.w3.org/WAI/tutorials/images/decision-tree">
										{ __(
											'Describe the purpose of the image'
										) }
									</ExternalLink>
									{ __(
										'Leave empty if the image is purely decorative.'
									) }
								</>
							}
						/>
					</PanelBody>
				</InspectorControls>
				<div
					className={ classnames( className, {
						'has-background': color.color,
						[ color.class ]: color.class,
					} ) }
				>
					{ this.renderMedia() }
					<div className="wp-block-mutualaidnyc-media-border__content">
						<RichText
							value={ content }
							tagName="p"
							onChange={ ( value ) =>
								setAttributes( { content: value } )
							}
						/>
					</div>
				</div>
			</>
		);
	}
}

export default compose( [
	withSelect( ( select, props ) => {
		const { getMedia } = select( 'core' );
		const {
			attributes: { mediaId },
			isSelected,
		} = props;
		return {
			image: mediaId && isSelected ? getMedia( mediaId ) : null,
		};
	} ),
	withNotices,
	withColors( 'color', { textColor: 'color' } ),
] )( MediaBorderEdit );
