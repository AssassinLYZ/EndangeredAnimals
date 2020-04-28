/**
 * File _groundwp-single-select-popup.js
 *
 * Selection pop up for single posts
 */
/**
 * Internal dependencies
 */
import GroundWPSelectionPopup from './_groundwp-selection-popup';

export default function singleSelectionPopup() {
	const singleSelectData = { ... selectionPopupData };

	selectionPopupData = undefined;

	new GroundWPSelectionPopup( '.' + singleSelectData.class, singleSelectData.items, singleSelectData.strings.copy );
}
