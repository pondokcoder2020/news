/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

import ClassicTestEditor from '@ckeditor/ckeditor5-core/tests/_utils/classictesteditor';
import Enter from '@ckeditor/ckeditor5-enter/src/enter';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import GFMDataProcessor from '@ckeditor/ckeditor5-markdown-gfm/src/gfmdataprocessor';
import Plugin from '@ckeditor/ckeditor5-core/src/plugin';

import CodeBlockEditing from '../src/codeblockediting';

// A simple plugin that enables the GFM data processor.
class CodeBlockIntegration extends Plugin {
	constructor( editor ) {
		super( editor );
		editor.data.processor = new GFMDataProcessor( editor.data.viewDocument );
	}
}

function getEditor( initialData = '' ) {
	return ClassicTestEditor
		.create( initialData, {
			plugins: [ CodeBlockIntegration, CodeBlockEditing, Enter, Paragraph ]
		} );
}

describe( 'CodeBlock - integration', () => {
	describe( 'with Markdown GFM', () => {
		it( 'should be loaded and returned from the editor (for plain text)', async () => {
			const editor = await getEditor(
				'```\n' +
				'test()\n' +
				'```'
			);

			expect( editor.getData() ).to.equal(
				'```plaintext\n' +
				'test()\n' +
				'```'
			);

			await editor.destroy();
		} );
		it( 'should be loaded and returned from the editor (for defined language)', async () => {
			const editor = await getEditor(
				'```javascript\n' +
				'test()\n' +
				'```'
			);

			expect( editor.getData() ).to.equal(
				'```javascript\n' +
				'test()\n' +
				'```'
			);

			await editor.destroy();
		} );
	} );
} );
