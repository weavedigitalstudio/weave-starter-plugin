/**
 * About tab.
 *
 * Displays plugin information, version, and useful links.
 */
import { useState } from '@wordpress/element';
import {
	Card,
	CardBody,
	CardHeader,
	ExternalLink,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function AboutTab() {
	/* global weaveStarterPlugin */
	const version = window.weaveStarterPlugin?.version || '1.0.0';
	const iconUrl = window.weaveStarterPlugin?.iconUrl || '';
	const [ iconError, setIconError ] = useState( false );

	return (
		<div style={ { marginTop: '16px' } }>
			<Card>
				<CardHeader>
					<div style={ { display: 'flex', alignItems: 'center', gap: '12px' } }>
						{ iconUrl && ! iconError ? (
							<img
								src={ iconUrl }
								alt={ __( 'Plugin icon', 'weave-starter-plugin' ) }
								width={ 48 }
								height={ 48 }
								style={ { borderRadius: '4px' } }
								onError={ () => setIconError( true ) }
							/>
						) : (
							<span
								className="dashicons dashicons-portfolio"
								style={ { fontSize: '48px', width: '48px', height: '48px' } }
							/>
						) }
						<h2 style={ { margin: 0 } }>
							{ __( 'Weave Starter Plugin', 'weave-starter-plugin' ) }
						</h2>
					</div>
				</CardHeader>
				<CardBody>
					<p>
						{ __(
							'A modern WordPress plugin scaffold for Weave Digital Studio and HumanKind. Built with namespaced PHP functions, React settings, Gutenberg blocks, and the Interactivity API.',
							'weave-starter-plugin'
						) }
					</p>
					<p>
						<strong>
							{ __( 'Version:', 'weave-starter-plugin' ) }
						</strong>{ ' ' }
						{ version }
					</p>
					<p>
						<strong>
							{ __( 'Author:', 'weave-starter-plugin' ) }
						</strong>{ ' ' }
						<ExternalLink href="https://weave.co.nz">
							Weave Digital Studio
						</ExternalLink>
					</p>
					<p>
						<strong>
							{ __( 'Repository:', 'weave-starter-plugin' ) }
						</strong>{ ' ' }
						<ExternalLink href="https://github.com/weavedigitalstudio/weave-starter-plugin">
							GitHub
						</ExternalLink>
					</p>
				</CardBody>
			</Card>

			<Card style={ { marginTop: '16px' } }>
				<CardHeader>
					<h2 style={ { margin: 0 } }>
						{ __( 'Resources', 'weave-starter-plugin' ) }
					</h2>
				</CardHeader>
				<CardBody>
					<ul>
						<li>
							<ExternalLink href="https://developer.wordpress.org/block-editor/">
								{ __(
									'Block Editor Handbook',
									'weave-starter-plugin'
								) }
							</ExternalLink>
						</li>
						<li>
							<ExternalLink href="https://wordpress.github.io/gutenberg/?path=/docs/docs-introduction--page">
								{ __(
									'WordPress Components Storybook',
									'weave-starter-plugin'
								) }
							</ExternalLink>
						</li>
						<li>
							<ExternalLink href="https://developer.wordpress.org/plugins/wordpress-org/">
								{ __(
									'Plugin Developer Handbook',
									'weave-starter-plugin'
								) }
							</ExternalLink>
						</li>
					</ul>
				</CardBody>
			</Card>
		</div>
	);
}
