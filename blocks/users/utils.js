
export const isEmpty = (value) => {
	// Check for falsy values: null, undefined, "", 0, false
	if (!value) {
		return true;
	}

	// Check for array-like objects (arrays, strings)
	if (typeof value === 'object' && typeof value.length === 'number') {
		return value.length === 0;
	}

	// Check for objects with enumerable properties
	if (typeof value === 'object') {
		return Object.keys(value).length === 0;
	}

	// Everything else is considered non-empty.
	return false;
}
