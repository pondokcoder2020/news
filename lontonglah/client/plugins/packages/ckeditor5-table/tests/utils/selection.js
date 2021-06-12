/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import VirtualTestEditor from '@ckeditor/ckeditor5-core/tests/_utils/virtualtesteditor';
import TableSelection from '../../src/tableselection';
import TableEditing from '../../src/tableediting';

import { setData as setModelData } from '@ckeditor/ckeditor5-engine/src/dev-utils/model';
import { modelTable } from '../_utils/utils';
import { getSelectedTableCells, getSelectionAffectedTableCells, getTableCellsContainingSelection } from '../../src/utils/selection';

describe( 'table utils', () => {
	let editor, model, tableSelection, modelRoot;

	beforeEach( async () => {
		editor = await VirtualTestEditor.create( {
			plugins: [ TableEditing, TableSelection, Paragraph ]
		} );

		model = editor.model;
		modelRoot = model.document.getRoot();
		tableSelection = editor.plugins.get( TableSelection );

		setModelData( model, modelTable( [
			[ '11[]', '12', '13' ],
			[ '21', '22', '23' ],
			[ '31', '32', '33' ]
		] ) );
	} );

	afterEach( async () => {
		await editor.destroy();
	} );

	describe( 'selection', () => {
		describe( 'getSelectedTableCells()', () => {
			let selection;

			beforeEach( () => {
				selection = model.document.selection;
			} );

			it( 'should return an empty array when a collapsed selection is anchored in a cell', () => {
				const firstCell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRange( writer.createPositionAt( firstCell, 0 ) ) );
				} );

				expect( getSelectedTableCells( selection ) ).to.be.empty;
			} );

			it( 'should return an empty array when a non-collapsed selection is anchored in a cell', () => {
				const firstCell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRangeIn( firstCell ) );
				} );

				expect( getSelectedTableCells( selection ) ).to.be.empty;
			} );

			it( 'should return an empty array when a non-cell node is selected', () => {
				const paragraph = modelRoot.getNodeByPath( [ 0, 0, 0, 0 ] );

				expect( paragraph.is( 'element', 'paragraph' ) ).to.be.true;

				model.change( writer => {
					writer.setSelection( writer.createRangeOn( paragraph ) );
				} );

				expect( getSelectedTableCells( selection ) ).to.be.empty;
			} );

			it( 'should return an empty array when an entire table is selected', () => {
				const table = modelRoot.getNodeByPath( [ 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRangeOn( table ) );
				} );

				expect( getSelectedTableCells( selection ) ).to.be.empty;
			} );

			it( 'should return two table cells', () => {
				const firstCell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );
				const lastCell = modelRoot.getNodeByPath( [ 0, 0, 1 ] );

				tableSelection.setCellSelection( firstCell, lastCell );

				expect( getSelectedTableCells( selection ) ).to.have.ordered.members( [
					firstCell, lastCell
				] );
			} );

			it( 'should return four table cells for diagonal selection', () => {
				const firstCell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );
				const lastCell = modelRoot.getNodeByPath( [ 0, 1, 1 ] );

				tableSelection.setCellSelection( firstCell, lastCell );

				expect( getSelectedTableCells( selection ) ).to.have.ordered.members( [
					firstCell,
					modelRoot.getNodeByPath( [ 0, 0, 1 ] ),
					modelRoot.getNodeByPath( [ 0, 1, 0 ] ),
					lastCell
				] );
			} );

			it( 'should return row table cells', () => {
				const firstCell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );
				const lastCell = modelRoot.getNodeByPath( [ 0, 0, 2 ] );

				tableSelection.setCellSelection( firstCell, lastCell );

				expect( getSelectedTableCells( selection ) ).to.have.ordered.members( [
					firstCell,
					modelRoot.getNodeByPath( [ 0, 0, 1 ] ),
					lastCell
				] );
			} );

			it( 'should return column table cells', () => {
				const firstCell = modelRoot.getNodeByPath( [ 0, 0, 1 ] );
				const lastCell = modelRoot.getNodeByPath( [ 0, 2, 1 ] );

				tableSelection.setCellSelection( firstCell, lastCell );

				expect( getSelectedTableCells( selection ) ).to.have.ordered.members( [
					firstCell,
					modelRoot.getNodeByPath( [ 0, 1, 1 ] ),
					lastCell
				] );
			} );

			it( 'should return cells in source order despite backward selection and forward ranges', () => {
				const leftCell = modelRoot.getNodeByPath( [ 0, 0, 1 ] );
				const rightCell = modelRoot.getNodeByPath( [ 0, 0, 2 ] );

				editor.model.change( writer => {
					writer.setSelection(
						[ writer.createRangeOn( leftCell ), writer.createRangeOn( rightCell ) ],
						{ backward: true }
					);
				} );

				expect( Array.from( tableSelection.getSelectedTableCells() ) ).to.deep.equal( [
					leftCell, rightCell
				] );
			} );

			it( 'should return cells in source order despite backward selection and backward ranges', () => {
				const leftCell = modelRoot.getNodeByPath( [ 0, 0, 1 ] );
				const rightCell = modelRoot.getNodeByPath( [ 0, 0, 2 ] );

				editor.model.change( writer => {
					writer.setSelection(
						[ writer.createRangeOn( rightCell ), writer.createRangeOn( leftCell ) ],
						{ backward: true }
					);
				} );

				expect( Array.from( tableSelection.getSelectedTableCells() ) ).to.deep.equal( [
					leftCell, rightCell
				] );
			} );

			// Backward direction does not have to equal ranges in the reversed order.
			it( 'should return cells in source order despite forward selection and backward ranges', () => {
				const leftCell = modelRoot.getNodeByPath( [ 0, 0, 1 ] );
				const rightCell = modelRoot.getNodeByPath( [ 0, 0, 2 ] );

				editor.model.change( writer => {
					writer.setSelection( [ writer.createRangeOn( rightCell ), writer.createRangeOn( leftCell ) ] );
				} );

				expect( Array.from( tableSelection.getSelectedTableCells() ) ).to.deep.equal( [
					leftCell, rightCell
				] );
			} );

			it( 'should return cells in source order despite selection with mixed range order', () => {
				const leftCell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );
				const midCell = modelRoot.getNodeByPath( [ 0, 0, 1 ] );
				const rightCell = modelRoot.getNodeByPath( [ 0, 0, 2 ] );

				editor.model.change( writer => {
					writer.setSelection( [
						writer.createRangeOn( rightCell ),
						writer.createRangeOn( leftCell ),
						writer.createRangeOn( midCell )
					] );
				} );

				expect( Array.from( tableSelection.getSelectedTableCells() ) ).to.deep.equal( [
					leftCell, midCell, rightCell
				] );
			} );
		} );

		describe( 'getTableCellsContainingSelection()', () => {
			let selection;

			beforeEach( () => {
				selection = model.document.selection;
			} );

			it( 'should return an array with a cell when a selection is anchored in it', () => {
				const firstCell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRange( writer.createPositionAt( firstCell, 0 ) ) );
				} );

				expect( getTableCellsContainingSelection( selection ) ).to.have.ordered.members( [ firstCell ] );
			} );

			it( 'should return an array with a cell when a selection range is anchored in its descendant', () => {
				const cell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );
				const paragraph = modelRoot.getNodeByPath( [ 0, 0, 0, 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRange(
						writer.createPositionAt( paragraph, 0 ),
						writer.createPositionAt( paragraph, 1 )
					) );
				} );

				expect( getTableCellsContainingSelection( selection ) ).to.have.ordered.members( [
					cell
				] );
			} );

			it( 'should return an array with cells when multiple collapsed selection ranges are anchored in them', () => {
				const cellA = modelRoot.getNodeByPath( [ 0, 0, 0 ] );
				const cellB = modelRoot.getNodeByPath( [ 0, 1, 0 ] );

				model.change( writer => {
					writer.setSelection( [
						writer.createRange( writer.createPositionAt( cellA, 0 ) ),
						writer.createRange( writer.createPositionAt( cellB, 0 ) )
					] );
				} );

				expect( getTableCellsContainingSelection( selection ) ).to.have.ordered.members( [
					cellA,
					cellB
				] );
			} );

			it( 'should return an array with cells when multiple non–collapsed selection ranges are anchored in them', () => {
				const cellA = modelRoot.getNodeByPath( [ 0, 0, 0 ] );
				const cellB = modelRoot.getNodeByPath( [ 0, 1, 0 ] );

				model.change( writer => {
					writer.setSelection( [
						writer.createRangeIn( cellA ),
						writer.createRangeIn( cellB )
					] );
				} );

				expect( getTableCellsContainingSelection( selection ) ).to.have.ordered.members( [
					cellA,
					cellB
				] );
			} );

			it( 'should return an empty array when an entire cell is selected', () => {
				const cell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRangeOn( cell ) );
				} );

				expect( getTableCellsContainingSelection( selection ) ).to.be.empty;
			} );

			it( 'should return an empty array when an entire table is selected', () => {
				const table = modelRoot.getNodeByPath( [ 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRangeOn( table ) );
				} );

				expect( getTableCellsContainingSelection( selection ) ).to.be.empty;
			} );

			it( 'should return an empty array when unrelated elements host selection ranges', () => {
				setModelData( model, '<paragraph>foo</paragraph>' );

				const paragraph = modelRoot.getNodeByPath( [ 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRange( writer.createPositionAt( paragraph, 1 ) ) );
				} );

				expect( getTableCellsContainingSelection( selection ) ).to.be.empty;
			} );
		} );

		describe( 'getSelectionAffectedTableCells()', () => {
			let selection;

			beforeEach( () => {
				selection = model.document.selection;
			} );

			it( 'should return completely selected cells (if there are any)', () => {
				const firstCell = modelRoot.getNodeByPath( [ 0, 0, 0 ] );
				const lastCell = modelRoot.getNodeByPath( [ 0, 0, 1 ] );

				tableSelection.setCellSelection( firstCell, lastCell );

				expect( Array.from( getSelectionAffectedTableCells( selection ) ) ).to.have.ordered.members( [
					firstCell, lastCell
				] );
			} );

			it( 'should return cells when selection ranges are starting in them', () => {
				const cellA = modelRoot.getNodeByPath( [ 0, 0, 0 ] );
				const cellB = modelRoot.getNodeByPath( [ 0, 1, 0 ] );

				model.change( writer => {
					writer.setSelection( [
						writer.createRange( writer.createPositionAt( cellA, 0 ) ),
						writer.createRange( writer.createPositionAt( cellB, 0 ) )
					] );
				} );

				expect( getSelectionAffectedTableCells( selection ) ).to.have.ordered.members( [
					cellA,
					cellB
				] );
			} );

			it( 'should return an empty array if no cells are selected and no selection ranges start in any cell', () => {
				const table = modelRoot.getNodeByPath( [ 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRangeOn( table ) );
				} );

				expect( getSelectionAffectedTableCells( selection ) ).to.be.empty;

				setModelData( model, '<paragraph>foo</paragraph>' );

				const paragraph = modelRoot.getNodeByPath( [ 0 ] );

				model.change( writer => {
					writer.setSelection( writer.createRange( writer.createPositionAt( paragraph, 1 ) ) );
				} );

				expect( getSelectionAffectedTableCells( selection ) ).to.be.empty;
			} );
		} );
	} );
} );
